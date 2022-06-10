<?php

namespace App\Http\Controllers\API\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;

class VNPayController extends BaseController
{
  public function create(Request $request)
  {
    $queryReturnUrl = "?step=2&token=" . $request->token;

    $vnp_TmnCode = config('vnpay.vnp_TmnCode');
    $vnp_HashSecret = config('vnpay.vnp_HashSecret');
    $vnp_Url = config('vnpay.vnp_Url');
    $vnp_Returnurl = config('vnpay.vnp_Returnurl') . $queryReturnUrl;
    $vnp_TxnRef = strtoupper(Str::random(12));
    $vnp_OrderInfo = config('vnpay.vnp_OrderInfo');
    $vnp_OrderType = config('vnpay.vnp_OrderType');
    $vnp_Amount = $request->total_amount * 100;
    $vnp_Locale = config('vnpay.vnp_Locale');
    $vnp_IpAddr = request()->ip();

    $inputData = array(
      "vnp_Version" => "2.0.0",
      "vnp_TmnCode" => $vnp_TmnCode,
      "vnp_Amount" => $vnp_Amount,
      "vnp_Command" => "pay",
      "vnp_CreateDate" => date('YmdHis'),
      "vnp_CurrCode" => "VND",
      "vnp_IpAddr" => $vnp_IpAddr,
      "vnp_Locale" => $vnp_Locale,
      "vnp_OrderInfo" => $vnp_OrderInfo,
      "vnp_OrderType" => $vnp_OrderType,
      "vnp_ReturnUrl" => $vnp_Returnurl,
      "vnp_TxnRef" => $vnp_TxnRef,
      "vnp_BankCode" => "NCB"
    );

    ksort($inputData);
    $query = "";
    $i = 0;
    $hashdata = "";
    foreach ($inputData as $key => $value) {
      if ($i == 1) {
        $hashdata .= '&' . $key . "=" . $value;
      } else {
        $hashdata .= $key . "=" . $value;
        $i = 1;
      }
      $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }

    $vnp_Url = $vnp_Url . "?" . $query;
    if (isset($vnp_HashSecret)) {
      $vnpSecureHash = hash('sha256', $vnp_HashSecret . $hashdata);
      $vnp_Url .= 'vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;
    }

    return $this->respondData($vnp_Url);
  }
}
