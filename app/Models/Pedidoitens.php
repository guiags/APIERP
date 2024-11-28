<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Pedido;

use Illuminate\Database\Eloquent\Model;

class Pedidoitens extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'idproduto';
    public $table = 'pedido_itens';
    protected $fillable = ['idpedido', 'numitem', 'idproduto', 'codpreco', 'quantidade', 'vrunit', 'vrtotal', 'codbarras', 'percdesc', 'vrdesc', 'percacres', 'vracres', 'perccomis', 'vrcomis', 'unidade', 'percdescunit', 'vrdescunit', 'vrunitoriginal', 'percacresunit', 'vracresunit'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id');
    }

    public function produto()
    {
        return $this->hasOne(Produto::class, 'codprod'); // Ajuste a chave estrangeira conforme sua tabela
    }
}
