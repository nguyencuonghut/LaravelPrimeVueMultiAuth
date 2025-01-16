<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    protected $fillable = [
        'code',
        'title',
        'material_id',
        'quality_id',
        'packing',
        'origin',
        'delivery_condition',
        'payment_condition',
        'certificate',
        'other_term',
        'freight_charge',
        'start_time',
        'end_time',
        'creator_id',
        'reviewer_id',
        'auditor_id',
        'approver_id',
        'reviewer_result',
        'auditor_result',
        'approver_result',
        'status',
        'is_competitive',
        'close_reason',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class);
    }

    public function auditor()
    {
        return $this->belongsTo(Admin::class);
    }

    public function approver()
    {
        return $this->belongsTo(Admin::class);
    }
}
