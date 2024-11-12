<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'codpessoa';
    public $table = 'clientes';
    protected $fillable = ['codpessoa', 'nomepessoa', 'tipopessoa', 'cpfcnpj', 'inscestadual', 'email', 'telefone1', 'telefone2', 'celular1', 'celular2', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'uf', 'cep', 'obs', 'datadocvenc', 'bloqueado', 'obsbloq', 'idvendedor', 'novo'];
}
