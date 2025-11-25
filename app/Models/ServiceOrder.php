<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceOrder extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    // Converte dados automaticamente (booleano e data)
    protected $casts = [
        'is_urgent' => 'boolean',
        'due_date' => 'date',
    ];

    // Relacionamento: ODS pertence a uma Secretaria
    public function secretariat(): BelongsTo
    {
        return $this->belongsTo(Secretariat::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
