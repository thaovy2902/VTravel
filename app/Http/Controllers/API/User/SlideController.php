<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\SlideResource;
use App\Models\Slide;

class SlideController extends BaseController
{
  public function __invoke()
  {
    return SlideResource::collection(
      Slide::active()->get()
    );
  }
}
