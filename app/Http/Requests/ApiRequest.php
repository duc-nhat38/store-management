<?php

namespace App\Http\Requests;

use App\Traits\HttpResponseJson;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ApiRequest extends FormRequest
{
    use HttpResponseJson;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }


    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return mixed
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation($validator)
    {
        if ($this->wantsJson()) {
            return $this->responseError('Data invalid.', $validator->errors()->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        throw new ValidationException($validator);
    }
}
