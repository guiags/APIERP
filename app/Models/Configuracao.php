<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Configuracao extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $table = 'configuracoes';
    protected $fillable = ['id', 'usacomisregresdesc', 'utilizaindicepreco', 'validaestneg', 'bloqestneg', 'bloqclidebvenc', 'bloqclilimitediasvenc', 'bloqclidiasvencatecor', 'bloqclidiasvencaposcor', 'precocasasdecimais', 'adicobsclinoped', 'carregalistaprodvazia'];

}
