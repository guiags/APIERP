<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Pedidoitens;

use Illuminate\Database\Eloquent\Model;

class Pedidoitensgrades extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'idproduto';
    public $table = 'pedido_itens_grades';
    protected $fillable = ['idpedido', 'itemped', 'idproduto', 'codgrade', 'nomegrade', 'coditgrade', 'nomeitgrade', 'codgradepai', 'nomegradepai', 'coditgradepai', 'nomeitgradepai', 'codbarras', 'quantidade'];

    public function pedidoitens()
    {
        return $this->belongsTo(Pedidoitens::class, 'idproduto', 'idpedido');
    }

}
