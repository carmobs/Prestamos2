<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'fk_id_empleado', 'id_empleado');
    }

    use HasFactory;
    protected $table = 'prestamo';
    protected $primaryKey = 'id_prestamo';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fk_id_empleado;
    protected $fecha_solicitud;
    protected $monto;
    protected $plazo;
    protected $fecha_scr;
    protected $tasa_mental;
    protected $pago_fijo_cap;
    protected $fecha_ini_desc;
    protected $fecha_fin_desc;
    protected $saldo_actual;
    protected $estado;
    protected $fillable = ['fk_id_empleado', 'fecha_solicitud', 'monto', 'plazo', 'fecha_scr', 'tasa_mental', 'pago_fijo_cap', 'fecha_ini_desc', 'fecha_fin_desc', 'saldo_actual', 'estado'];
    public $timestamps = false;
}
