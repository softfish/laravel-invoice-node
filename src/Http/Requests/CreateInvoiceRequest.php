<?php
namespace Feikwok\InvoiceNode\Http\Requests;

use \Feikwok\InvoiceNode\Http\Requests\Traits\WebErrorValidationTrail;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CreateInvoiceRequest extends FormRequest
{
    use WebErrorValidationTrail;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        return Auth::check();
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
            'client_name' => 'required',
            'email' => 'required|email',
            'business_name' => 'max:255',
            'business_number' => 'min:4|max:50',
            'address' => 'required',
            'is_taxable' => 'required|boolean',
            'tax_rate' => 'required_if:is_taxable,true|numeric',
            'template' => 'required|max:150',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'client_name.required' => 'Please provide the name of person you are invoicing to.',
            'business_name.max' => 'Company number could not be more than 255 characters.',
            'business_number.max' => 'Business number need to be between 4 to 50 characters.'
        ];
    }

    /**
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

    public function validateResolved()
    {
        // TODO: Implement validateResolved() method.
    }
}
