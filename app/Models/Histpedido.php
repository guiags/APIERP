<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Histpedidoitens;

class Histpedido extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $table = 'hist_pedido';
    protected $fillable = ['id', 'emissao', 'status', 'idcliente', 'entrega', 'percdesc', 'vrdesc', 'vrbruto', 'vrliquido', 'obs', 'idformapag1', 'idformapag2', 'idplanopag1', 'idplanopag2', 'vrpago1', 'vrpago2', 'idvendedor', 'vrcomis', 'perccomis'];

    public function itens()
    {
        return $this->hasMany(Histpedidoitens::class, 'idpedido'); // Ajuste a chave estrangeira conforme sua tabela
    }
}
