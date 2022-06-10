<?php

namespace App\Http\Controllers\API\User;

use App\Models\User;
use App\Models\Order;
use App\Events\NewOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\OrderResource;
use App\Notifications\NewOrderNotification;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends BaseController
{
  public function __construct()
  {
    $this->middleware('jwt.auth');
  }

  public function index()
  {
    $query = array(
      'perPage' =>  5,
      'status' => request()->status ? (int) request()->status : 1,
      'q' => request()->q,
    );
    $data = $this->getQueryData($query);

    return OrderResource::collection($data);
  }

  public function store(Request $request)
  {
    $user = auth()->user();
    $request['code'] = time() . '-' . date('mY');
    if ((bool) $request->is_paid === false) {
      $request['status'] = 2;
    }
    $order = $user->orders()->create($request->all());

    $this->decreaseRemainingCode($request->discount_code);
    $this->sendNotificationToAdmin($order);

    return $this->respondData(new OrderResource($order->load(['payment', 'tour', 'user'])), Response::HTTP_CREATED);
  }

  public function update(Request $request, Order $order)
  {
    $order->update($request->only(['status', 'reason_cancel']));

    return $this->respondData(new OrderResource($order->load(['payment', 'tour', 'user'])), Response::HTTP_ACCEPTED);
  }

  private function getQueryData($query)
  {
    $results = Order::with(['payment', 'tour', 'user'])
      ->where('user_id', auth()->user()->id);
    if ($query['status']) {
      $results = $results->where('status', $query['status']);
    }
    if ($query['q']) {
      $results = $results->search($query['q']);
    }
    $results = $results->latest()->paginate($query['perPage']);

    return $results;
  }

  private function decreaseRemainingCode($code)
  {
    DB::table('discount_codes')->where('code', $code)->decrement('remaining');
  }

  private function sendNotificationToAdmin($order)
  {
    $admins = User::hasRole(2)->get();
    Notification::send($admins, new NewOrderNotification($order));
  }
}
