<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Support\Str;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\DiscountCodeResource;
use App\Mail\DiscountCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class DiscountCodeController extends BaseController
{
  public function index()
  {
    $query = array(
      'sortBy' => request()->sortBy ?? 'created_at',
      'orderBy' => request()->orderBy ?? 'desc',
      'perPage' => request()->perPage ? (int) request()->perPage : 5,
    );
    $data = $this->getQueryData($query);

    return DiscountCodeResource::collection($data);
  }

  public function store(Request $request)
  {
    $quantity = $request->quantity ? (int) $request->quantity : 1;
    if ($quantity === 1) {
      $request['code'] = strtoupper(Str::random(6));
      DiscountCode::create($request->all());

      return $this->respondSuccess('', Response::HTTP_CREATED);
    }
    if ($quantity > 1) {
      $arrayInsert = [];
      for ($i = 0; $i < $quantity; $i++) {
        array_push($arrayInsert, [
          'code' => strtoupper(Str::random(6)),
          'percent' => $request->percent,
          'remaining' => $request->remaining,
          'expire' => $request->expire,
          'created_at' => now(),
          'updated_at' => now(),
        ]);
      }
      DiscountCode::insert($arrayInsert);

      return $this->respondSuccess('', Response::HTTP_CREATED);
    }
  }

  public function send(Request $request)
  {
    $id = $request->discountCodeId;
    $numberUser = $request->numberUser;
    $discountCode = DiscountCode::findOrFail($id);

    $users = $this->fetchRandomUsers($numberUser);

    $this->sendMail($users, $discountCode);

    return $this->respondSuccess(config('message.send_success'));
  }

  private function getQueryData($query)
  {
    return DiscountCode::orderBy($query['sortBy'], $query['orderBy'])
      ->paginate($query['perPage']);
  }

  private function fetchRandomUsers($numberUser)
  {
    return User::hasRole(3)
      ->active()
      ->inRandomOrder()
      ->take($numberUser)
      ->get();
  }

  private function sendMail($users, $discountCode)
  {
    foreach ($users as $user) {
      Mail::to($user)->send(new DiscountCodeMail($discountCode));
    }
  }
}
