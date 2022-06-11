<?php

namespace App\Http\Requests\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateUserRequest extends FormRequest
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
            'name' => config('validation.user_name'),
            'email' => 'unique:users|' . config('validation.email'),
            'password' => config('validation.password'),
            'phone_number' =>  config('validation.phone_number'),
            'address' => config('validation.address'),
            'is_active' => config('validation.boolean'),
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên không được trống',
            'name.string' => 'Tên phải là một chuỗi',
            'name.max' => 'Tên tối đa :max ký tự',
            'email.unique' => 'Email đã tồn tại',
            'email.required' => 'Email không được trống',
            'email.string' => 'Email phải là một chuỗi',
            'email.email' => 'Không đúng định dạng email',
            'email.max' => 'Email tối đa :max ký tự',
            'password.required' => 'Mật khẩu không được trống',
            'password.between' => 'Mật khẩu từ :min - :max ký tự',
            'phone_number.between' => 'Phone Number từ :min - :max ký tự',
            'address.string' => 'Address phải là một chuỗi',
            'address.max' => 'Address tối đa :max ký tự',
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
