<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ctreceber extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'coddoc';
    public $table = 'ctreceber';
    protected $fillable = ['coddoc', 'numdoc', 'dataemis', 'datavenc', 'codcli', 'nomecli', 'vrdoc', 'conta', 'planopag', 'datapago', 'vrpago', 'situacao'];
}
