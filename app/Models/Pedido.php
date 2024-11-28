<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Pedidoitens;
use App\Models\Cliente;


use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $table = 'pedido';
    protected $fillable = ['id', 'emissao', 'tipo', 'status', 'idcliente', 'entrega', 'percdesc', 'vrdesc', 'vrbruto', 'vrliquido', 'obs', 'idformapag1', 'idformapag2', 'idplanopag1', 'idplanopag2', 'vrpago1', 'vrpago2', 'idvendedor', 'vrcomis', 'perccomis'];

    public function itens()
    {
        return $this->hasMany(Pedidoitens::class, 'idpedido'); // Ajuste a chave estrangeira conforme sua tabela
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'codpessoa'); // Ajuste a chave estrangeira conforme sua tabela
    }

}
