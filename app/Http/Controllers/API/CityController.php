<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\API\BaseController;

class CityController extends BaseController
{
  public function __invoke()
  {
    $cities = File::get(base_path('config/json/cities.json'));
    $data = json_decode($cities);

    return $this->respondData($data);
  }
}
