<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Viatico extends Model
{
    protected $fillable = [
        'fecha', 'saldo_inicial', 'motivo', 'monto_viatico', 'saldo_final'
    ];
}
