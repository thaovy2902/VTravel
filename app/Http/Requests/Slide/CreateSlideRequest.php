<?php

namespace App\Http\Requests\Slide;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateSlideRequest extends FormRequest
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
            'title' => config('validation.title'),
            'image' => config('validation.title'),
            'link' => 'max:255',
            'is_active' => config('validation.boolean'),
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Tiêu đề không được trống',
            'title.string' => 'Tiêu đề phải là một chuỗi',
            'title.max' => 'Tiêu đề tối đa :max ký tự',
            'image.required' => 'Ảnh không được trống',
            'image.max' => 'Tên ảnh tối đa :max ký tự',
            'link.max' => 'Link tối đa :max ký tự',
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
