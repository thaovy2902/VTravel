<?php

namespace App\Http\Requests\Feedback;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class SendFeedbackRequest extends FormRequest
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
            'email' => config('validation.email'),
            'phone_number' => 'required|' . config('validation.phone_number'),
            'type' => 'required',
            'content' => 'required|string|min:10',
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
            'phone_number.required' => 'Phone Number is required.',
            'phone_number.between' => 'Phone Number must be between :min and :max characters long.',
            'type.required' => 'Type is required.',
            'content.required' => 'Content is required.',
            'content.string' => 'Content must be a string.',
            'content.min' => 'Content must be at least :min characters long.',
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
