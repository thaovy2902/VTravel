<?php

namespace App\Http\Requests\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
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
            'phone_number.between' => 'Số điện thoại từ :min - :max ký tự',
            'address.string' => 'Địa chỉ phải là một chuỗi',
            'address.max' => 'Địa chỉ tối đa :max ký tự',
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
