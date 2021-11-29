<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Response;

class TaskRequest extends FormRequest
{


         /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    protected function failedValidation(Validator $validator)
    {

        $response=Response::json(
            [
                'status' => 422,
                'message' => $validator->messages()->first(),
                'errors' => $validator->errors(),
            ],
            200
        );

        if (request()->expectsJson()) {
            throw new  HttpResponseException($response);
        }

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

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
            'name'=>['required','max:191'],
            'description'=>['required','max:225'],
            'date' => ['required','date_format:Y-m-d','after:today'],
        ];
    }
}
