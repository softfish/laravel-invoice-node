<?php

namespace Feikwok\InvoiceNode\Http\Requests\Traits;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

trait ApiErrorValidationTrail
{
    /**
     * The have this function to overwrite the failedValidation function and make sure it won't redirect and properly
     * handle the json error response here.
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
        return response()->json([
            'success' => false,
            'error' => $errors
        ], JsonResponse::HTTP_OK);
    }
}
