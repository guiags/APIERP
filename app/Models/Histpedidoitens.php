<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Histpedido;

class Histpedidoitens extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'idpedido';
    public $table = 'hist_pedido_itens';
    protected $fillable = ['idpedido', 'numitem', 'idproduto', 'codpreco', 'quantidade', 'vrunit', 'vrtotal', 'codbarras', 'percdesc', 'vrdesc', 'percacres', 'vracres', 'perccomis', 'vrcomis', 'unidade'];

    public function pedido()
    {
        return $this->belongsTo(Histpedido::class, 'id');
    }

    public function produto()
    {
        return $this->hasOne(Produto::class, 'codprod', 'idproduto'); // Class, chave da tabela estrangeira, chave estrangeira
    }
}
