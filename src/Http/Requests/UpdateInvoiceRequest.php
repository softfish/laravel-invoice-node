<?php

namespace Feikwok\InvoiceNode\Http\Requests;

use Feikwok\InvoiceNode\Http\Requests\Traits\ApiErrorValidationTrail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateInvoiceRequest extends FormRequest
{
    use ApiErrorValidationTrail;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:innov_invoices',
            'client_name' => 'required',
            'business_name' => 'max:255',
            'business_number' => 'min:4|max:50',
            'address' => 'required',
            'email' => 'email',
            'phone' => 'max:50',
            'is_taxable' => 'required|boolean',
            'tax_rate' => 'required_if:is_taxable,true|numeric',
            'template' => 'required|max:150',
            'enable_cc' => 'boolean',
        ];
    }
}
