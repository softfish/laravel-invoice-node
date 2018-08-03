<?php
namespace Feikwok\InvoiceNova\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BillEntry extends Model
{
    protected $table = 'innov_bill_entries';

    protected $fillable = [
        'invoice_id',
        'position',
        'description',
        'charge',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model){
            if (empty($model->position)) {
                $maxOrder = DB::raw('SELECT MAX(position) FROM innov_bill_entries WHERE invoice_id = '.$model->invoice_id);
                $model->position = $maxOrder + 1;
                if ($model->charge === '') {
                    $model->charge = null;
                }
            }
        });

        static::created(function($model){
            if ($model->invoice->status === 'new') {
                $model->invoice->update(['status' => 'pending']);
            }
        });
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}