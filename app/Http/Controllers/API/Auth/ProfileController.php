<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Auth\Profile\UpdatePasswordRequest;
use App\Http\Requests\Auth\Profile\UpdateUserDataRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends BaseController
{
  public function __construct()
  {
    $this->middleware('jwt.auth');
  }

  public function setUserData(UpdateUserDataRequest $request)
  {
    $user = $request->user();

    $user->update($request->all());

    return $this->respondData([
      'user' => new UserResource($user->load(['role'])),
      'message' => config('message.changeprofile_success')
    ]);
  }

  public function setPassword(UpdatePasswordRequest $request)
  {
    $user = $request->user();

    $currentPassword = $request->currentPassword;
    $newPassword = $request->newPassword;

    if (Hash::check($newPassword, $user->password)) {
      return $this->respondError(config('message.changepassword_error1'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    if (!Hash::check($currentPassword, $user->password)) {
      return $this->respondError(config('message.changepassword_error2'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    $user->update(['password' => $newPassword]);

    return $this->respondSuccess(config('message.changepassword_success'));
  }

  public function setAvatar()
  {
    $user = request()->user();
    $newAvatar = request()->avatar;

    $user->update(['avatar' => $newAvatar]);

    return $this->respondData([
      'user' => new UserResource($user->load(['role'])),
      'message' => config('message.changeavatar_success')
    ]);
  }
}
