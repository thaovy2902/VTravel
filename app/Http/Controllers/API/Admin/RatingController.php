<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Rating;
use Illuminate\Http\Request;
use App\Http\Resources\RatingResource;
use App\Http\Controllers\API\BaseController;
use Symfony\Component\HttpFoundation\Response;

class RatingController extends BaseController
{
  public function index()
  {
    $query = array(
      'sortBy' => request()->sortBy ?? 'created_at',
      'orderBy' => request()->orderBy ?? 'desc',
      'perPage' => request()->perPage ? (int) request()->perPage : 5,
    );
    $data = $this->getQueryData($query);

    return RatingResource::collection($data);
  }

  public function update(Request $request, Rating $rating)
  {
    $rating->update($request->only('is_active'));

    return $this->respondData(new RatingResource($rating->load(['user', 'tour'])), Response::HTTP_ACCEPTED);
  }

  public function destroy(Rating $rating)
  {
    $rating->delete();

    return $this->respondSuccess(config('message.delete_success'));
  }

  private function getQueryData($query)
  {
    return Rating::with(['user', 'tour'])
      ->orderBy($query['sortBy'], $query['orderBy'])
      ->paginate($query['perPage']);
  }
}
