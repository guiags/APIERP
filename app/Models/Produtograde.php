<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Produto;

use Illuminate\Database\Eloquent\Model;

class Produtograde extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'codprod';
    public $table = 'produtos_grades';
    protected $fillable = ['codprod', 'codgrade', 'nomegrade', 'coditgrade', 'nomeitgrade', 'codgradepai', 'nomegradepai', 'coditgradepai', 'nomeitgradepai', 'preco', 'codbarras'];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'codprod');
    }
}
