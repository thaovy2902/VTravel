<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\OrderResource;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends BaseController
{
  public function index()
  {
    $query = array(
      'perPage' => request()->perPage ? (int) request()->perPage : 5,
      'status' => (int) request()->status ?? 0,
      'from_date' => request()->from_date,
      'to_date' => request()->to_date,
      'q' => request()->q,
    );
    $data = $this->getQueryData($query);

    return OrderResource::collection($data);
  }

  public function update(Request $request, Order $order)
  {
    if ((int) $request->status === 3) {
      $request['is_paid'] = true;
      $order->update($request->only(['status', 'is_paid']));
    }
    if ((int) $request->status === 4) {
      $request['is_paid'] = false;
      $order->update($request->only(['status', 'reason_cancel', 'is_paid']));
    }

    $this->sendMail($order);

    return $this->respondData(new OrderResource($order->load(['payment', 'tour', 'user'])), Response::HTTP_ACCEPTED);
  }

  public function destroy(Order $order)
  {
    $order->delete();

    return $this->respondSuccess(config('message.delete_success'));
  }

  private function getQueryData($query)
  {
    $results = Order::with(['payment', 'tour', 'user'])
      ->where('status', $query['status']);
    if ($query['from_date'] && $query['to_date']) {
      $results = $results->whereBetween('date_depart', [$query['from_date'], $query['to_date']]);
    }
    if ($query['q']) {
      $results = $results->search($query['q']);
    }
    $results = $results->latest()->paginate($query['perPage']);

    return $results;
  }

  private function sendMail($order)
  {
    Mail::to($order->customer_email)->send(new OrderStatusUpdatedMail($order));
  }
}
