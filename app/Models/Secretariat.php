<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Secretariat extends Model
{
    // Desativa proteção de preenchimento (para o MVP)
    protected $guarded = [];

    // Relacionamento: Uma secretaria tem muitas ODS
    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
