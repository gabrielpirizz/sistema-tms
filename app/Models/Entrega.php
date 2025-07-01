<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entrega extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'transportadora_id',
        'volumes',
        'remetente_nome',
        'destinatario_nome',
        'destinatario_cpf',
        'destinatario_endereco',
        'destinatario_cep',
        'destinatario_estado',
    ];


    public function transportadora(): BelongsTo
    {
        return $this->belongsTo(Transportadora::class);
    }

    public function rastreamentos(): HasMany
    {
        return $this->hasMany(Rastreamento::class);
    }
}
