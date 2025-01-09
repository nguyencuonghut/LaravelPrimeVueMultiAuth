<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quantity extends Model
{
    protected $fillable = ['tender_id', 'qty', 'unit', 'delivery_time'];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }
}
