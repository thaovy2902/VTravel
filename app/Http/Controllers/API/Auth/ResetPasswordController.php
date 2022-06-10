<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\BaseController;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Auth\ChangePasswordRequest;

class ResetPasswordController extends BaseController
{
    public function process(ChangePasswordRequest $request)
    {
        return $this->getPasswordResetTableRow($request)->count() > 0 ? $this->changePassword($request) : $this->tokenNotFoundResponse();
    }

    private function getPasswordResetTableRow($request)
    {
        return DB::table('password_resets')->where([
            'email' => $request->email,
            'token' => $request->resetToken,
        ]);
    }

    private function changePassword($request)
    {
        $user = User::where('email', $request->email)->first();
        $user->update(['password' => $request->password]);
        $this->getPasswordResetTableRow($request)->delete();

        return $this->respondSuccess(config('message.changepassword_success'));
    }

    private function tokenNotFoundResponse()
    {
        return $this->respondError(config('message.resetpassword_error'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
