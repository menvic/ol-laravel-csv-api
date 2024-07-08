<?php

namespace App\Http\Requests;

use App\CarModelEnum;
use App\Models\UserData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $fillableFields = (new UserData())->getFillable();
        $fieldString = implode(',', $fillableFields);

        return [
            'search' => ['string', 'max:100'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'offset' => ['nullable', 'integer', 'min:0'],
            'sort_by' => ['nullable', 'string', 'in:' . $fieldString],
            'order_by' => ['nullable', 'string', 'in:asc,desc'],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * This method overrides the default failedValidation behavior to throw an
     * HttpResponseException with a JSON response containing validation errors.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function failedValidation(Validator $validator): HttpResponseException
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ], 400));
    }

    /**
     * Get the query parameters that apply to the request for scribe Api docs.
     *
     * @return array
     */
    public function queryParameters()
    {
        return [
            'search' => ['example' => 'A'],
            'limit' => ['example' => 10],
            'offset' => ['example' => 0],
            'sort_by' => ['example' => 'age'],
            'order_by' => ['example' => 'asc'],
        ];
    }
}
