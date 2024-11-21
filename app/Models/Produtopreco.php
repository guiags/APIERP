<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Produto;

use Illuminate\Database\Eloquent\Model;

class Produtopreco extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'codprod';
    public $table = 'produtos_precos';
    protected $fillable = ['codprod', 'codpreco', 'preco', 'descricao'];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'codprod');
    }

}
