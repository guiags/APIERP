<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Empresa extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $table = 'empresa';
    protected $fillable = ['id', 'nomefantasia', 'endereco', 'bairro', 'cidade', 'uf', 'telefone', 'celular', 'cnpj'];
}
