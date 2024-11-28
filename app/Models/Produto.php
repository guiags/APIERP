<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Produtopreco;
use App\Models\Produtolote;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'codprod';
    public $table = 'produtos';
    protected $fillable = ['codprod', 'nome', 'descrcompleta', 'referencia', 'codgrupo', 'unidade', 'codbarras', 'preco', 'fotoprod', 'usagrade', 'estoque', 'usalote', 'inativo'];

    public function precos()
    {
        return $this->hasMany(Produtopreco::class, 'codprod'); // Ajuste a chave estrangeira conforme sua tabela
    }

    public function lotes()
    {
        return $this->hasMany(Produtolote::class, 'codprod'); // Ajuste a chave estrangeira conforme sua tabela
    }
    public function pedidoitens()
    {
        return $this->belongsTo(Pedidoitens::class, 'idproduto');
    }
}
