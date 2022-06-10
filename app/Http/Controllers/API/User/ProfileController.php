<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;

class ProfileController extends BaseController
{
  public function __invoke()
  {
    return $this->respondData(auth()->user());
  }
}
