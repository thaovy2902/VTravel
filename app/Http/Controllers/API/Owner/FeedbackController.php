<?php

namespace App\Http\Controllers\API\Owner;

use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Http\Resources\FeedbackResource;
use App\Http\Controllers\API\BaseController;
use Symfony\Component\HttpFoundation\Response;

class FeedbackController extends BaseController
{
  public function index()
  {
    $query = array(
      'sortBy' => request()->sortBy ?? 'created_at',
      'orderBy' => request()->orderBy ?? 'desc',
      'perPage' => request()->perPage ? (int) request()->perPage : 5,
    );
    $data = $this->getQueryData($query);

    return FeedbackResource::collection($data);
  }

  public function update(Request $request, Feedback $feedback)
  {
    $feedback->update($request->only('is_seen'));

    return $this->respondData(new FeedbackResource($feedback), Response::HTTP_ACCEPTED);
  }

  public function destroy(Feedback $feedback)
  {
    $feedback->delete();

    return $this->respondSuccess(config('message.delete_success'));
  }

  private function getQueryData($query)
  {
    return Feedback::orderBy($query['sortBy'], $query['orderBy'])
      ->paginate($query['perPage']);
  }
}
