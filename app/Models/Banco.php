<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banco extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'TokenRenovar';
    public $table = 'bancos';
    protected $fillable = ['TokenRenovar', 'NomeBanco'];
}
