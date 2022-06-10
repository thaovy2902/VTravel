<?php

namespace App\Http\Controllers\API\User;

use App\Models\Payment;
use App\Http\Resources\PaymentCollection;
use App\Http\Controllers\API\BaseController;

class PaymentController extends BaseController
{
  public function __invoke()
  {
    return new PaymentCollection(Payment::all());
  }
}
