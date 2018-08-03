<?php
namespace Feikwok\InvoiceNova\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Feikwok\InvoiceNova\Events\InvoiceHasBeenIssued;
use Feikwok\InvoiceNova\Events\InvoiceHasBeenPaid;
use Feikwok\InvoiceNova\Http\Requests\CreateInvoiceRequest;
use Feikwok\InvoiceNova\Http\Requests\UpdateInvoiceRequest;
use Feikwok\InvoiceNova\Models\Invoice;
use Illuminate\Http\Request;

class InvoicesApiController extends Controller
{
    /**
     * Fetch a list of invoice and apply filter when given
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Invoice::with('bill_entries');
        if ($request->has('searchStr') && $request->get('searchStr') != '')
        {
            $search = explode(' ', $request->get('searchStr'));
            // Basic Search Logic here
            $query = $query->where(function($query) use ($search){
                $baseCheckFields = [
                    'ref', 'client_name', 'business_name', 'business_number', 'email',
                    'phone'
                ];
                foreach ($baseCheckFields as $field) {
                    foreach ($search as $str) {
                        $query->orWhere($field, 'LIKE', '%' . $str . '%');
                    }
                }
            });
        }
        $query = $query->orderBy('created_at', 'DESC');
        $invoices = $query->paginate(50);

        return response()->json([
            'success' => true,
            'invoices' => $invoices
        ], 200);
    }

    /**
     * Show a single Invoice detail through API
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $invoice = Invoice::with('bill_entries')->find($id);

        if (empty($invoice)) {
            flash('Invoice not found')->danger()->important();
            return redirect()->back();
        }

        return response()->json([
            'success' => true,
            'invoice' => $invoice
        ], 200);
    }

    /**
     * Saving the new invoice
     *
     * @param CreateInvoiceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateInvoiceRequest $request)
    {
        $invoice = (new Invoice)->create($request->all());

        return response()->json([
            'success' => true,
            'invoice' => $invoice,
        ]);
    }

    /**
     * Updating an existing invoice
     *
     * @param UpdateInvoiceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateInvoiceRequest $request)
    {
        $invoice = Invoice::with('bill_entries')->find($request->get('id'));
        if ($invoice->is_editable) {
            $invoice->update([
                'client_name' => $request->get('client_name'),
                'business_name' => $request->get('business_name'),
                'business_number' => $request->get('business_number'),
                'address' => $request->get('address'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'is_taxable' => $request->get('is_taxable'),
                'tax_rate' => $request->get('tax_rate'),
                'template' => $request->get('template'),
                'status' => $request->get('status'),
            ]);

            switch($invoice->status) {
                case 'issued':
                    event(new InvoiceHasBeenIssued($invoice));
                    break;
//            case 'paid':
//                break;
            }

            return response()->json([
                'success' => true,
                'invoice' => $invoice,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Invoice is not editable.',
            ]);
        }
    }
}