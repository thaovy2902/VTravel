<?php

namespace App\Http\Requests\Tour;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateTourRequest extends FormRequest
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
            'image' => 'required',
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
            'image.required' => 'Ảnh không được trống',
            'depart.required' => 'Departure không được trống',
            'description.required' => 'Mô tả không được trống',
            'description.string' => 'Mô tả phải là một chuỗi',
            'from_place_name.required' => 'Origin không được trống',
            'from_place_name.string' => 'Origin phải là một chuỗi',
            'from_place_name.max' => 'Origin tối đa :max ký tự',
            'to_place_code.required' => 'Origin không được trống',
            'transport.required' => 'Transport không được trống',
            'transport.string' => 'Transport phải là một chuỗi',
            'transport.max' => 'Transport tối đa :max ký tự',
            'number_days.required' => 'Time không được trống',
            'number_days.numeric' => 'Time phải là một số',
            'number_persons.required' => 'Số người không được trống',
            'number_persons.numeric' => 'Số người phải là một số',
            'price_default.required' => 'Adults không được trống',
            'price_default.numeric' => 'Adults phải là một số',
            'price_children.required' => 'Children (2-12) không được trống',
            'price_children.numeric' => 'Children (2-12) phải là một số',
            'price_baby.required' => 'Infants (0-2) không được trống',
            'price_baby.numeric' => 'Infants (0-2) phải là một số',
            'category_id.required' => 'Category không được trống',
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
