<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = ['code', 'name', 'status'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
