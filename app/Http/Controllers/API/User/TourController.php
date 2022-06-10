<?php

namespace App\Http\Controllers\API\User;

use App\Models\Tour;
use App\Http\Resources\TourResource;
use App\Http\Controllers\API\BaseController;

class TourController extends BaseController
{
  public function index()
  {
    $query = request()->query();
    $arrayFilter = array(
      'q' => $query['q'] ?? '',
      'category' => $query['category'] ?? '',
      'depart' => $query['depart'] ?? '',
      'toPlace' => $query['toPlace'] ?? '',
      'rating' => $query['rating'] ?? '',
      'minPrice' => $query['minPrice'] ?? '',
      'maxPrice' => $query['maxPrice'] ?? '',
      'featured' => $query['featured'] ?? '',
      'sortBy' => $query['sortBy'] ?? '',
      'orderBy' => $query['orderBy'] ?? '',
    );

    $data = $this->filterData($arrayFilter);

    return TourResource::collection($data);
  }

  public function getTourNew()
  {
    return TourResource::collection(
      Tour::with(['category', 'ratings'])->active()->latest()->take(6)->get()
    );
  }

  public function getTourFeatured()
  {
    return TourResource::collection(
      Tour::with(['category', 'ratings'])->active()->featured()->take(6)->get()
    );
  }

  public function getTourData(Tour $tour)
  {
    return $this->respondData(new TourResource($tour->load(['category', 'ratings'])));
  }

  public function show($slug)
  {
    $tour = Tour::where('slug', $slug)->first();
    if (!$tour) {
      return $this->respondError('Not found');
    }

    return $this->respondData(new TourResource($tour->load(['category', 'ratings'])));
  }

  public function getRelatedTour()
  {
    $toPlace = explode(',', request()->toPlace);
    $toPlaceRand = $toPlace[array_rand($toPlace)];
    $currentTourId = request()->currentTourId;
    if ($toPlace && $currentTourId) {
      $tours = Tour::with(['category', 'ratings'])
        ->where('id', '<>', $currentTourId)
        ->whereJsonContains('to_place_code', $toPlaceRand)
        ->active()->inRandomOrder()->latest()->take(3)->get();

      return TourResource::collection($tours);
    }
  }

  private function filterData($arr)
  {
    $results = Tour::with(['category', 'ratings'])->active();
    if ($arr['q']) {
      $results = $results->search($arr['q']);
    }
    if ($arr['category']) {
      $results = $results->where('category_id', $arr['category']);
    }
    if ($arr['depart']) {
      $results = $results->where('depart', $arr['depart']);
    }
    if ($arr['toPlace']) {
      $results = $results->whereJsonContains('to_place_code', $arr['toPlace']);
    }
    if ($arr['minPrice'] && $arr['maxPrice']) {
      $results = $results->whereBetween('price_default', [$arr['minPrice'], $arr['maxPrice']]);
    }
    if ($arr['featured']) {
      $results = $results->where('is_featured', $arr['featured']);
    }
    if ($arr['sortBy'] && $arr['orderBy']) {
      $results = $results->orderBy($arr['sortBy'], $arr['orderBy']);
    }

    return $results->paginate(9);
  }
}
