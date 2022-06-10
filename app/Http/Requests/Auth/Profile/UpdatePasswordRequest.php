<?php

namespace App\Http\Requests\Auth\Profile;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'currentPassword' => config('validation.password'),
            'newPassword' => config('validation.password'),
        ];
    }

    public function messages()
    {
        return [
            'currentPassword.required' => 'Mật khẩu cũ không được trống',
            'currentPassword.between' => 'Mật khẩu cũ từ :min - :max ký tự',
            'newPassword.required' => 'Mật khẩu mới không được trống',
            'newPassword.between' => 'Mật khẩu mới từ :min - :max ký tự',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json([
            'message' => $errors,
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
