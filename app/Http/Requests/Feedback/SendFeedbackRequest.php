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
            'name.required' => 'Tên không được trống',
            'name.string' => 'Tên phải là một chuỗi',
            'name.max' => 'Tên tối đa :max ký tự',
            'email.unique' => 'Email đã tồn tại',
            'email.required' => 'Email không được trống',
            'email.string' => 'Email phải là một chuỗi',
            'email.email' => 'Không đúng định dạng email',
            'email.max' => 'Email tối đa :max ký tự',
            'phone_number.required' => 'Số điện thoại không được trống',
            'phone_number.between' => 'Số điện thoại từ :min - :max ký tự',
            'type.required' => 'Loại không được trống',
            'content.required' => 'Nội dung không được trống',
            'content.string' => 'Nội dung phải là một chuỗi',
            'content.min' => 'Nội dung tối thiểu :min ký tự',
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
