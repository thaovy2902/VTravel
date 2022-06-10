<?php

namespace App\Http\Controllers\API\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\BaseController;

class ApplyDiscountController extends BaseController
{
  public function apply(Request $request)
  {
    return $this->getDiscountCodeTableRow($request)->count() > 0 ? $this->existData($request) : $this->codeNotFoundResponse();
  }

  private function getDiscountCodeTableRow($request)
  {
    return DB::table('discount_codes')->where('code', $request->discountCode);
  }

  private function existData($request)
  {
    $dateNow = date('Y-m-d');
    $code = $this->getDiscountCodeTableRow($request)->first();
    if (strtotime($code->expire) < strtotime($dateNow)) {
      return $this->codeExpiredResponse();
    }
    if ($code->remaining === 0) {
      return $this->codeOutOfUseResponse();
    }

    return $this->respondData($code);
  }

  private function codeExpiredResponse()
  {
    return $this->respondError(config('message.discount_expired'));
  }

  private function codeOutOfUseResponse()
  {
    return $this->respondError(config('message.discount_outofuse'));
  }

  private function codeNotFoundResponse()
  {
    return $this->respondError(config('message.discount_notfound'));
  }
}
