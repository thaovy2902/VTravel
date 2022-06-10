<?php

namespace App\Http\Requests\Tour;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTourRequest extends FormRequest
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
            'name' => config('validation.tour_name'),
            'depart' => 'required',
            'description' => 'required|string',
            'from_place_name' => config('validation.title'),
            'to_place_code' => 'required',
            'transport' => config('validation.title'),
            'number_days' => config('validation.numeric'),
            'number_persons' => config('validation.numeric'),
            'price_default' => config('validation.numeric'),
            'price_children' => config('validation.numeric'),
            'price_baby' => config('validation.numeric'),
            'is_featured' => 'boolean',
            'is_active' => 'required|boolean',
            'category_id' => config('validation.numeric'),
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên tour không được trống',
            'name.string' => 'Tên tour phải là một chuỗi',
            'name.max' => 'Tên tour tối đa :max ký tự',
            'depart.required' => 'Khởi hành không được trống',
            'description.required' => 'Mô tả không được trống',
            'description.string' => 'Mô tả phải là một chuỗi',
            'from_place_name.required' => 'Nơi đi không được trống',
            'from_place_name.string' => 'Nơi đi phải là một chuỗi',
            'from_place_name.max' => 'Nơi đi tối đa :max ký tự',
            'to_place_code.required' => 'Nơi đi không được trống',
            'transport.required' => 'Phương tiện không được trống',
            'transport.string' => 'Phương tiện phải là một chuỗi',
            'transport.max' => 'Phương tiện tối đa :max ký tự',
            'number_days.required' => 'Số ngày không được trống',
            'number_days.numeric' => 'Số ngày phải là một số',
            'number_persons.required' => 'Số người không được trống',
            'number_persons.numeric' => 'Số người phải là một số',
            'price_default.required' => 'Giá người lớn không được trống',
            'price_default.numeric' => 'Giá người lớn phải là một số',
            'price_children.required' => 'Giá trẻ em không được trống',
            'price_children.numeric' => 'Giá trẻ em phải là một số',
            'price_baby.required' => 'Giá em bé không được trống',
            'price_baby.numeric' => 'Giá em bé phải là một số',
            'category_id.required' => 'Loại tour không được trống',
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
