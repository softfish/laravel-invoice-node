<?php
namespace Feikwok\InvoiceNode\Http\Requests;

use Feikwok\InvoiceNode\Http\Requests\Traits\ApiErrorValidationTrail;
use Illuminate\Foundation\Http\FormRequest;

class CreateBillEntryRequest extends FormRequest
{
    use ApiErrorValidationTrail;

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
            'description' => 'required'
        ];
    }
}
