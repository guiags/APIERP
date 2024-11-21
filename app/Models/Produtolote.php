<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Produtolote extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'codprod';
    public $table = 'produtos_lotes';
    protected $fillable = ['codprod', 'numlote', 'datafab', 'dataval', 'estoque'];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'codprod');
    }
}
