<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Configvendedor extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'idvendedor';
    public $table = 'config_vendedor';
    protected $fillable = ['idvendedor', 'exibeclibloq', 'listaprodvazia', 'naoexibeimgprod', 'sincronizacao'];
}
