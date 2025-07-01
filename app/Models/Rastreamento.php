<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rastreamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'entrega_id',
        'message',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function entrega(): BelongsTo
    {
        return $this->belongsTo(Entrega::class);
    }
}