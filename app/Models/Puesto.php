<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
    use HasFactory;
    protected $table = 'puesto';
    protected $primaryKey = 'id_puesto';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $nombre;
    protected $sueldo;
    protected $fillable = ['nombre', 'sueldo'];
    public $timestamps = false;
}

