<?php

namespace App\Http\Requests\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\ReCaptcha;

class ChangePasswordRequest extends FormRequest
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
            'email' => config('validation.email'),
            'password' => config('validation.password'),
            'recaptcha' => ['required', new ReCaptcha]
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email không được trống',
            'email.string' => 'Email phải là một chuỗi',
            'email.email' => 'Không đúng định dạng email',
            'email.max' => 'Email tối đa :max ký tự',
            'password.required' => 'Mật khẩu không được trống',
            'password.between' => 'Mật khẩu từ :min - :max ký tự',
            'recaptcha.required' => 'Bạn chưa chọn Recaptcha',
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
