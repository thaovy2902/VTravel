<?php

namespace App\Http\Controllers\API\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\BaseController;

class VerifyTransactionController extends BaseController
{
  public function verify(Request $request)
  {
    return $this->getVerifyTransactionTableRow($request)->count() > 0 ? $this->successResponse($request) : $this->codeNotFoundResponse();
  }

  private function getVerifyTransactionTableRow($request)
  {
    return DB::table('verify_transaction')->where([
      'email' => $request->email,
      'code' => $request->verifyCode,
    ]);
  }

  private function successResponse($request)
  {
    DB::table('verify_transaction')->where('email', $request->email)
      ->delete();

    return $this->respondSuccess([
      'isSuccess' => true
    ]);
  }

  private function codeNotFoundResponse()
  {
    return $this->respondError([
      'isSuccess' => false,
      'message' => config('message.verifytransaction_error')
    ]);
  }
}
