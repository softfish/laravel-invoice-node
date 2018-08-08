<?php
/**
 * Created by PhpStorm.
 * User: arkade
 * Date: 29/7/18
 * Time: 9:15 AM
 */

namespace Feikwok\InvoiceNode\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'innov_payments';

    protected $fillable = [
        'invoice_id',
        'internal_status',
        'gateway',
        'raw_data',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}