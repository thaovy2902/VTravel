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
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name may not be greater than :max characters.',
            'email.unique' => 'Email is already exists.',
            'email.required' => 'Email is required.',
            'email.string' => 'Email must be a string.',
            'email.email' => 'The email address you have entered is not valid.',
            'email.max' => 'Email may not be greater than :max characters.',
            'password.required' => 'Password is required.',
            'password.between' => 'Password must be between :min and :max characters long.',
            'phone_number.between' => 'Phone Number must be between :min and :max characters long.',
            'address.string' => 'Address must be a string.',
            'address.max' => 'Address may not be greater than :max characters.',
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
