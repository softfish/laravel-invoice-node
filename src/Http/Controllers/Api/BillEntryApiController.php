<?php

namespace Feikwok\InvoiceNova\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Feikwok\InvoiceNova\Http\Requests\CreateBillEntryRequest;
use Feikwok\InvoiceNova\Models\Invoice;

class BillEntryApiController extends Controller
{
    /**
     * Create a new Bill Entry
     *
     * @param $id
     * @param CreateBillEntryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($id, CreateBillEntryRequest $request)
    {
        $invoice = Invoice::find($id);
        $nextPosition = $invoice->bill_entries->count() + 1;
        $invoice->bill_entries()->create([
            'position' => $nextPosition,
            'description' => $request->get('description'),
            'charge' => $request->get('charge')
        ]);

        // Reload the invoice
        $invoice = Invoice::with('bill_entries')->find($invoice->id);
        return response()->json([
            'success' => true,
            'invoice' => $invoice,
        ]);

    }

    /**
     * Delete a existing bill entry
     *
     * @param $invoice_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($invoice_id, $id)
    {
        $invoice = Invoice::find($invoice_id);
        if (!empty($invoice)) {
            $invoice->bill_entries->each(function($item, $key) use ($id){
                if ($item->id === (int) $id) {
                    $item->delete();
                    return false;
                }
            });

            return response()->json([
                'success' => true,
                'invoice' => Invoice::with('bill_entries')->find($invoice->id),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Invoice '.$invoice_id.' not found',
            ]);
        }
    }
}
