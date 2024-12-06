<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produtoimagem extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'codprod';
    public $table = 'produtos_imagens';
    protected $fillable = ['codprod', 'id', 'nome', 'diretorio'];
}
