<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transportadora extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'cnpj',
        'fantasia',
    ];

    public function entregas(): HasMany
    {
        return $this->hasMany(Entrega::class);
    }
}
