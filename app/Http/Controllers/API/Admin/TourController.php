<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Tour;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\TourResource;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Tour\CreateTourRequest;
use App\Http\Requests\Tour\UpdateTourRequest;
use Symfony\Component\HttpFoundation\Response;

class TourController extends BaseController
{
  public function index()
  {
    $query = array(
      'sortBy' => request()->sortBy ?? 'created_at',
      'orderBy' => request()->orderBy ?? 'desc',
      'perPage' => request()->perPage ? (int) request()->perPage : 5,
      'q' => request()->q,
    );
    $data = $this->getQueryData($query);

    return TourResource::collection($data);
  }

  public function store(CreateTourRequest $request)
  {
    $requestHandled = $this->handleRequest($request);
    $requestHandled['code'] = substr(time(), 6)  . '-' . date('dmY');
    $tour = Tour::create($requestHandled->all());

    return $this->respondData(new TourResource($tour->load(['category', 'ratings'])), Response::HTTP_CREATED);
  }

  public function update(UpdateTourRequest $request, Tour $tour)
  {
    $requestHandled = $this->handleRequest($request);
    $tour->update($requestHandled->all());

    return $this->respondData(new TourResource($tour->load(['category', 'ratings'])), Response::HTTP_ACCEPTED);
  }

  public function updateStatus(Request $request, Tour $tour)
  {
    if (!is_null($tour)) {
      if (asset($request->is_active)) {
        $tour->update($request->only('is_active'));
      }
      if (asset($request->is_featured)) {
        $tour->update($request->only('is_featured'));
      }

      return $this->respondData(new TourResource($tour->load(['category', 'ratings'])), Response::HTTP_ACCEPTED);
    }
  }

  public function destroy(Tour $tour)
  {
    $tour->delete();

    return $this->respondSuccess(config('message.delete_success'));
  }

  private function getQueryData($query)
  {
    $results = Tour::with(['category', 'ratings']);
    if ($query['q']) {
      $results = $results->search($query['q']);
    }
    $results = $results->orderBy($query['sortBy'], $query['orderBy'])
      ->paginate($query['perPage']);

    return $results;
  }

  private function handleRequest($request)
  {
    $cities = json_decode(File::get(base_path('config/json/cities.json')));
    $toPlace = $request->to_place_code;
    $len = count($toPlace);
    $toPlaceName = '';
    $i = 0;

    foreach ($toPlace as $key => $value) {
      foreach ($cities as $val) {
        if ($i == $len - 1 && $value == $val->code) {
          $toPlaceName .= $val->name;
          break;
        }
        if ($value == $val->code) {
          $toPlaceName .= $val->name . ', ';
        }
      }
      $i++;
    }

    $request['slug'] = Str::slug($request->name);
    $request['to_place_code'] = $toPlace;
    $request['to_place_name'] = $toPlaceName;

    return $request;
  }
}
