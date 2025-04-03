<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{
    use HasFactory;
    protected $table = 'abono';
    protected $primaryKey = 'id_abono';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fk_id_prestamo;
    protected $num_abono;
    protected $fecha;
    protected $monto_capital;
    protected $monto_otros;
    protected $monto_colocado;
    protected $saldo_pendiente;
    protected $fillable = ['fk_id_prestamo', 'num_abono', 'fecha', 'monto_capital', 'monto_otros', 'monto_colocado', 'saldo_pendiente'];
    public $timestamps = false;
}
