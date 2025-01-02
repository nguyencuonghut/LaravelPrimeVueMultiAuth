<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quality extends Model
{
    protected $fillable = ['material_id', 'detail', 'status'];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
