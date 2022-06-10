<?php

namespace App\Http\Controllers\API\User;

use App\Models\Tour;
use App\Models\Rating;
use App\Http\Resources\RatingResource;
use App\Http\Requests\Rating\RatingRequest;
use App\Http\Controllers\API\BaseController;
use Symfony\Component\HttpFoundation\Response;

class RatingController extends BaseController
{
  protected $tour;

  public function __construct(Tour $tour)
  {
    $this->tour = $tour;
    $this->middleware('jwt.auth', ['except' => ['index', 'getTopRating', 'getAvgRating']]);
  }

  public function index($slug)
  {
    $tour = Tour::where('slug', $slug)->first();
    $ratings = $tour->ratings()->with(['user', 'tour'])->active()->latest()->paginate(5);

    return RatingResource::collection($ratings);
  }

  public function store(RatingRequest $request, $slug)
  {
    $tour = Tour::where('slug', $slug)->first();
    $request['tour_id'] = $tour->id;
    $rating = auth()->user()->ratings()->create($request->all());

    return $this->respondData(new RatingResource($rating->load(['user', 'tour'])), Response::HTTP_CREATED);
  }

  public function show($id)
  {
    //
  }

  public function update(RatingRequest $request, Rating $rating)
  {
    if ($rating->user_id !== auth()->user()->id) {
      return $this->respondUnauthorized();
    }
    $rating->update($request->all());

    return $this->respondData(new RatingResource($rating->load(['user', 'tour'])), Response::HTTP_ACCEPTED);
  }

  public function destroy(Rating $rating)
  {
    if ($rating->user_id !== auth()->user()->id) {
      return $this->respondUnauthorized();
    }
    $rating->delete();

    return $this->respondSuccess(config('message.delete_success'));
  }

  public function getTopRating()
  {
    $ratings = Rating::with(['user', 'tour'])->active()->latest('scores')->inRandomOrder()->take(10)->get();

    return RatingResource::collection($ratings);
  }

  public function getAvgRating($slug)
  {
    $tour = Tour::where('slug', $slug)->first();

    return $this->respondData([
      'avg' =>  $tour->ratings->avg('scores') ?? 0,
      'count' =>  $tour->ratings->count(),
    ]);
  }
}
