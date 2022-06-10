<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Slide;
use Illuminate\Http\Request;
use App\Http\Resources\SlideResource;
use App\Http\Controllers\API\BaseController;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Slide\CreateSlideRequest;
use App\Http\Requests\Slide\UpdateSlideRequest;

class SlideController extends BaseController
{
  public function index()
  {
    $query = array(
      'sortBy' => request()->sortBy ?? 'created_at',
      'orderBy' => request()->orderBy ?? 'desc',
      'perPage' => request()->perPage ? (int) request()->perPage : 5,
    );
    $data = $this->getQueryData($query);

    return SlideResource::collection($data);
  }

  public function store(CreateSlideRequest $request)
  {
    $slide = Slide::create($request->all());

    return $this->respondData(new SlideResource($slide), Response::HTTP_CREATED);
  }

  public function update(UpdateSlideRequest $request, Slide $slide)
  {
    $slide->update($request->all());

    return $this->respondData(new SlideResource($slide), Response::HTTP_ACCEPTED);
  }

  public function updateIsActive(Request $request, Slide $slide)
  {
    $slide->update($request->only('is_active'));

    return $this->respondData(new SlideResource($slide), Response::HTTP_ACCEPTED);
  }

  public function destroy(Slide $slide)
  {
    $slide->delete();

    return $this->respondSuccess(config('message.delete_success'));
  }

  private function getQueryData($query)
  {
    return Slide::orderBy($query['sortBy'], $query['orderBy'])
      ->paginate($query['perPage']);
  }
}
