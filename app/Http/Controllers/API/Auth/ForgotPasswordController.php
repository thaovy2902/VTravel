<?php

namespace App\Http\Controllers\API\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Auth\ForgotPasswordRequest;

class ForgotPasswordController extends BaseController
{
    public function sendMail(ForgotPasswordRequest $request)
    {
        if (!$this->validateEmail($request->email)) {
            return $this->failedResponse();
        }

        $this->send($request->email);
        return $this->successResponse();
    }

    private function validateEmail($email)
    {
        return !!User::where('email', $email)->first();
    }

    private function failedResponse()
    {
        return $this->respondError(config('message.email_notfound'));
    }

    private function send($email)
    {
        $token = $this->createToken($email);
        Mail::to($email)->send(new ResetPasswordMail($token));
    }

    private function createToken($email)
    {
        $oldToken = DB::table('password_resets')->where('email', $email)->first();
        if ($oldToken) {
            return $oldToken->token;
        }
        $token = Str::random(60);
        $this->saveToken($token, $email);

        return $token;
    }

    private function saveToken($token, $email)
    {
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
    }

    private function successResponse()
    {
        return $this->respondSuccess(config('message.forgotpassword_success'));
    }
}
