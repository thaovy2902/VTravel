<?php

namespace App\Http\Controllers\API\User;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\API\BaseController;
use App\Mail\VerifyTransactionMail;

class SendVerifyTransactionController extends BaseController
{
  public function sendMail(Request $request)
  {
    $this->send($request->email);

    return $this->successResponse();
  }

  private function send($email)
  {
    $code = $this->createCode($email);

    Mail::to($email)->send(new VerifyTransactionMail($code));
  }

  private function createCode($email)
  {
    $code = strtoupper(Str::random(6));
    $this->saveCode($code, $email);

    return $code;
  }

  private function saveCode($code, $email)
  {
    DB::table('verify_transaction')->insert([
      'email' => $email,
      'code' => $code,
      'created_at' => Carbon::now()
    ]);
  }

  private function successResponse()
  {
    return $this->respondSuccess(config('message.verifytransaction_success'));
  }
}
