<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContaBancaria extends Model
{
    use HasFactory;

    // Especificar o nome correto da tabela
    protected $table = 'contas_bancarias';

    protected $fillable = ['nome', 'saldo'];
}
