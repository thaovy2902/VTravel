<?php

namespace App\Http\Controllers\API;

use App\Models\Tour;
use Illuminate\Http\Request;
use App\Http\Resources\TourResource;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\DB;

class ChatbotController extends BaseController
{
  public function getTourByPrice(Request $request)
  {
    $minPrice = $request->minPrice;
    $maxPrice = $request->maxPrice;

    $data = Tour::with(['category', 'ratings'])
      ->whereBetween('price_default', [$minPrice, $maxPrice])
      ->active()->latest()->take(6)->get();

    return TourResource::collection($data);
  }

  public function getTourByDestination(Request $request)
  {
    $toPlace = $request->toPlace;

    $data = Tour::with(['category', 'ratings'])
      ->whereJsonContains('to_place_code', $toPlace)
      ->active()->latest()->take(6)->get();

    return TourResource::collection($data);
  }
}
