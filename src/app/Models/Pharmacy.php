<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    use HasFactory;

    protected $table = 'pharmacies';

    protected $primaryKey = 'id'; 

    public $incrementing = true;

    protected $fillable = [
        'nombre',
        'direccion',
        'latitud',
        'longitud',
    ];    

}