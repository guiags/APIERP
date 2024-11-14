<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $table = 'usuarios';
    protected $fillable = ['usuario','senha','idvendedor', 'nomevendedor','perccomiss','percdescmax','comisregrpercdesc','comisregrperccomis'];
    
}
