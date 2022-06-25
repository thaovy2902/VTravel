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
            'name.required' => 'Tour Name is required.',
            'name.string' => 'Tour Name must be a string.',
            'name.max' => 'Tour Name may not be greater than :max characters.',
            'image.required' => 'Image is required.',
            'depart.required' => 'Departure is required.',
            'description.required' => 'Description is required.',
            'description.string' => 'Description must be a string.',
            'from_place_name.required' => 'Origin is required.',
            'from_place_name.string' => 'Origin must be a string.',
            'from_place_name.max' => 'Origin may not be greater than :max characters.',
            'to_place_code.required' => 'Origin is required.',
            'transport.required' => 'Transport is required.',
            'transport.string' => 'Transport must be a string.',
            'transport.max' => 'Transport may not be greater than :max characters.',
            'number_days.required' => 'Time is required.',
            'number_days.numeric' => 'Time must be a number.',
            'number_persons.required' => 'Number of Participants is required.',
            'number_persons.numeric' => 'Number of Participants must be a number.',
            'price_default.required' => 'Adults is required.',
            'price_default.numeric' => 'Adults must be a number.',
            'price_children.required' => 'Children (2-12) is required.',
            'price_children.numeric' => 'Children (2-12) must be a number.',
            'price_baby.required' => 'Infants (0-2) is required.',
            'price_baby.numeric' => 'Infants (0-2) must be a number.',
            'category_id.required' => 'Category is required.',
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
