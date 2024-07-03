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
        $carModelNames = collect(CarModelEnum::cases())->pluck('name')->implode(',');

        return [
            'first_name' => ['nullable', 'string', 'max:50', 'required_without_all:' . $fieldString],
            'last_name' => ['nullable', 'string', 'max:50', 'required_without_all:' . $fieldString],
            'age' => ['nullable', 'integer', 'min:18', 'max:80', 'required_without_all:' . $fieldString],
            'gender' => ['nullable', 'in:male,female', 'required_without_all:' . $fieldString],
            'mobile_number' => ['nullable', 'string', 'max:25', 'required_without_all:' . $fieldString],
            'email' => ['nullable', 'string', 'email', 'max:100', 'required_without_all:' . $fieldString],
            'city' => ['nullable', 'string', 'max:100', 'required_without_all:' . $fieldString],
            'login' => ['nullable', 'string', 'max:50', 'required_without_all:' . $fieldString],
            'car_model' => ['nullable', 'in:' . $carModelNames, 'required_without_all:' . $fieldString],
            'salary' => ['nullable', 'integer', 'min:1000', 'max:5000', 'required_without_all:' . $fieldString],
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
            'first_name' => ['example' => 'A'],
            'last_name' => ['example' => 'A'],
            'age' => ['example' => 30],
            'gender' => ['example' => 'male'],
            'mobile_number' => ['example' => '123'],
            'email' => ['example' => 'test@gmail.com'],
            'city' => ['example' => 'London'],
            'login' => ['example' => 'A'],
            'car_model' => ['example' => 'BMW'],
            'salary' => ['example' => 1000],
            'limit' => ['example' => 10],
            'offset' => ['example' => 0],
            'sort_by' => ['example' => 'age'],
            'order_by' => ['example' => 'asc'],
        ];
    }
}
