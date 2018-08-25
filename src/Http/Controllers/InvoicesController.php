<?php
namespace Feikwok\InvoiceNode\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Feikwok\InvoiceNode\Events\InvoiceHasBeenIssued;
use Feikwok\InvoiceNode\Events\InvoiceHasBeenPaid;
use Feikwok\InvoiceNode\Events\InvoicePaymentCheckFailed;
use Feikwok\InvoiceNode\Models\Invoice;
use Feikwok\InvoiceNode\Models\Payment;
use Feikwok\InvoiceNode\Services\StripeApiService;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Stripe\Charge;
use Stripe\Stripe;

class InvoicesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('invoice-node::index');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($id)
    {
        $invoice = Invoice::with('bill_entries')->find($id);

        if (empty($invoice)) {
            flash('Invoice not found')->danger()->important();
            return redirect()->back();
        }

        return view('invoice-node::show', ['invoice' => $invoice]);
    }

    /**
     * Generate a PDF file and preview it from the browser
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function preview($id)
    {
        $invoice = Invoice::with('bill_entries')->find($id);

        if (empty($invoice)) {
            flash('Invoice not found')->danger()->important();
            return redirect()->back();
        }

        $qrImage = QrCode::format('png')->size(200)->generate(url('/innov/invoice/'.$invoice->ref.'/payment'));

        $pdf = \App::make('dompdf.wrapper');
        $pdf = $pdf->loadHTML(view('invoice-node::invoice.'.$invoice->template, ['invoice' => $invoice, 'qrImage' => $qrImage])->render());
        return $pdf->setPaper('a4')->stream();
    }

    /**
     * Generate teh PDF file and allow it to be downloaded it from the browser.
     *
     * @param $ref
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function downloadPDF($ref)
    {
        $invoice = Invoice::with('bill_entries')
            ->where('ref', $ref)
            ->first();

        if (empty($invoice)) {
            flash('Invoice not found')->danger()->important();
            return redirect()->back();
        }

        return $invoice->getInvoicePdf()->download('invoice-'.date('Ymd').'.pdf');
    }

    /**
     * Display the online payment page for the invoice
     *
     * @param $ref
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function showPayment($ref)
    {
        $invoice = Invoice::where('ref', $ref)->first();
        if (!$invoice->email) {
            throw new \Exception('Invoive must has an email for payment');
        }

        $sessionId = $invoice->getSessionToken();

        if (!empty($invoice)) {
            return view('invoice-node::payment', ['invoice' => $invoice, 'sessionId' => $sessionId]);
        } else {
            return view('invoice-node::paymentError', ['ref' => $ref]);
        }
    }

    /**
     * @param $ref
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPayment($ref, Request $request)
    {
        $sessionId = $request->get('sessionId');
        $token = JWT::decode($sessionId, env('APP_KEY'), ['HS256']);
        $invoice = Invoice::find($token->id);

        // First we need to record the payment first and set the internal status as pending validation
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'internal_status' => 'pending validation',
            'gateway' => 'stripe',
            'raw_data' => json_encode($request->all()),
        ]);

        Log::info('Check Session Token Expired...');
        if (Carbon::now()->lessThan($token->expired->date)) {

            // check the ref is correct too
            Log::info('Checking Invoice Ref Correct...');
            if ($invoice->ref === $token->ref) {

                // check the original url
                Log::info('Checking Request Origin...');
                if ($token->iss === env('APP_URL')) {
                    // Now we need to check the payment token is valid
                    Log::info('Checking Stripe Payment Token...');
                    $stripeService = new StripeApiService();
                    if ($stripeService->validatePayment($request->all())) {
                        // Now we can charge the customer
                        $result = $stripeService->createCharge([
                                        'invoice' => $invoice,
                                        'token' => $request->get('stripeToken')
                                    ]);

                        $invoice->status = ($result)? 'paid' : 'payment failed';
                        $invoice->save();

                        event(new InvoiceHasBeenPaid($invoice));

                        // Update the payment internal status here to confirmed
                        $payment->update([
                            'internal_status' => 'confirmed'
                        ]);
                        Log::info('Payment Confirmed');
                        return redirect()->back();
                    }
                }
            }
        }
        Log::error('Payment Validation Failed');
        $payment->update([
            'internal_status' => 'validation failed - manual check required'
        ]);

        // This is most likely the customer has left the page open for too long and the session has expired before the
        // refresh kick-in - very rarely. But will handle this manually for now. The invoice would still consider to be
        // paid because stripe has make the payment. This is just for the fallsafe check.
        event(new InvoicePaymentCheckFailed($invoice));

        return redirect()->back();
    }

    public function resendMail($id, Request $request)
    {
        if ($request->has('event')) {
            $invoice = Invoice::find($id);
            switch ($request->get('event')) {
                case 'invoice-issued':
                    event(new InvoiceHasBeenIssued($invoice));
                    flash('Invoice has been reissued to the customer.')->info()->important();
                    break;
                case 'payment-confirmed':
                    event(new InvoiceHasBeenPaid($invoice));
                    flash('Payment confirmation has been sent to the customer.')->info()->important();
                    break;
                default;
            }
        }

        return redirect()->back();
    }
}