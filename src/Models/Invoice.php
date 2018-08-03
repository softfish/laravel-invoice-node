<?php

namespace Feikwok\InvoiceNova\Models;

use Feikwok\InvoiceNova\Events\InvoiceHasBeenIssued;
use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Invoice extends Model
{
    protected $table = 'innov_invoices';

    protected $fillable = [
        'client_name',
        'business_name',
        'business_number',
        'email',
        'phone',
        'address',
        'status',
        'is_taxable',
        'tax_rate',
        'template',
    ];

    protected $appends = ['is_editable', 'tax', 'subtotal', 'total_amount'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model){
            if (!isset($model->ref)) {
                do {
                    $newRef = str_random(30);
                    $invoice = self::where('ref', $newRef)->first();
                }while(!empty($invoice));
                $model->ref = $newRef;
            }
        });

        static::saving(function($model){
            if ($model->status === 'issued') {
                $model->issued_at = Carbon::now();
            }

            if ($model->tax_rate > 0 && !$model->is_taxable) {
                $model->is_taxable = true;
            } else if ($model->tax_rate == 0) {
                $model->is_taxable = false;
            }
        });

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bill_entries()
    {
        return $this->hasMany(BillEntry::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client_profile()
    {
        return $this->belongsTo(ClientProfile::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the list of template file name from the package views folder
     *
     * @return array
     */
    public function loadAvailableTemplates()
    {
        // From the package
        $files = scandir(__DIR__.'/../../resources/views/invoice');
        // find the template name
        $templates = [];
        foreach ($files as $fileName) {
            if (str_contains($fileName, '.blade.php')) {
                $templates[] = str_replace('.blade.php', '', $fileName);
            }
        }

        // Include custom templates.
        if (file_exists(resource_path('/views/innov/invoice_templates'))) {
            $customFiles = scandir(resource_path('/views/innov/invoice_templates'));
            foreach ($customFiles as $fileName) {
                if (str_contains($fileName, '.blade.php')) {
                    $templates[] = str_replace('.blade.php', '', $fileName);
                }
            }
        }

        return $templates;
    }

    /**
     * @return bool
     */
    public function getIsEditableAttribute()
    {
        return in_array($this->status, ['pending', 'new']);
    }

    public function getTaxAttribute()
    {
        if ($this->is_taxable) {
            return $this->subtotal * $this->tax_rate;
        }
        return 0;
    }

    public function getSubtotalAttribute()
    {
        $subtotal = 0;
        foreach ($this->bill_entries as $bill) {
            if ($bill->charge != null) {
                $subtotal += $bill->charge;
            }
        }

        return $subtotal;
    }

    public function getTotalAmountAttribute()
    {
        return $this->subtotal + $this->tax;
    }

    /**
     * clear the old position number and reorder them again based on their current database positions
     */
    public function resetBillEntriesPosition()
    {
        $this->bill_entries->each(function ($item, $index) {
            $item->position = $index;
            $item->save();
        });
    }

    public function getSessionToken()
    {
        $token = [
            'iss' => env('APP_URL'),
            'ref' => $this->ref,
            'expired' => Carbon::now()->addMinutes(30),
            'id' => $this->id,
        ];

        return JWT::encode($token, env('APP_KEY'));
    }

    public function getInvoicePdf()
    {
        $pdf = \App::make('dompdf.wrapper');
        $qrImage = QrCode::format('png')->size(200)->generate(url('/innov/invoice/'.$this->ref.'/payment'));

        $pdf = $pdf->loadHTML(view('invoice-nova::invoice.'.$this->template, ['invoice' => $this, 'qrImage' => $qrImage])->render());
        return $pdf->setPaper('a4');
    }
}
