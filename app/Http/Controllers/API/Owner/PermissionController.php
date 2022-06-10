<?php

namespace App\Http\Controllers\API\Owner;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\PermissionResource;
use App\Http\Controllers\API\BaseController;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends BaseController
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

    return PermissionResource::collection($data);
  }

  public function update(Request $request, User $user)
  {
    if (!is_null($user)) {
      if (asset($request->is_active)) {
        $user->update($request->only('is_active'));
      }
      if (asset($request->role_id)) {
        $user->update($request->only('role_id'));
      }

      return $this->respondData(new PermissionResource($user->load(['role'])), Response::HTTP_ACCEPTED);
    }
  }

  private function getQueryData($query)
  {
    $results = User::with(['role'])->where('role_id', '!=', 1);
    if ($query['q']) {
      $results = $results->search($query['q']);
    }
    $results = $results->orderBy($query['sortBy'], $query['orderBy'])
      ->paginate($query['perPage']);

    return $results;
  }
}
