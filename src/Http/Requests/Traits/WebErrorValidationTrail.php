<?php

namespace Feikwok\InvoiceNode\Http\Requests\Traits;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

trait WebErrorValidationTrail
{
    /**
     * The have this function to overwrite the failedValidation function and make sure it properly
     * handle the web error response here.
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException($this->response($validator->errors()->toArray()));
    }
    /**
     *
     * @param array $errors
     * @return JsonResponse
     */
    private function response(array $errors) {
        foreach ($errors as $fieldName => $errorMsg) {
            flash($fieldName.' : '.implode(' ,', $errorMsg))->error()->important();
        }

        return redirect()->back();
    }
}
