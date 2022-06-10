<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Controllers\API\BaseController;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseController
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

    return UserResource::collection($data);
  }

  public function store(CreateUserRequest $request)
  {
    $request['role_id'] = 3;
    $user = User::create($request->all());

    return $this->respondData(new UserResource($user->load(['role'])), Response::HTTP_CREATED);
  }

  public function update(UpdateUserRequest $request, User $user)
  {
    $user->update($request->all());

    return $this->respondData(new UserResource($user->load(['role'])), Response::HTTP_ACCEPTED);
  }

  public function updateIsActive(Request $request, User $user)
  {
    $user->update($request->only('is_active'));

    return $this->respondData(new UserResource($user->load(['role'])), Response::HTTP_ACCEPTED);
  }

  public function destroy(User $user)
  {
    $user->delete();

    return $this->respondSuccess(config('message.delete_success'));
  }

  private function getQueryData($query)
  {
    $results = User::with(['role'])->hasRole(3);
    if ($query['q']) {
      $results = $results->search($query['q']);
    }
    $results = $results->orderBy($query['sortBy'], $query['orderBy'])
      ->paginate($query['perPage']);

    return $results;
  }
}
