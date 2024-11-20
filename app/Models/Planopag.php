<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Planopag extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $table = 'planospag';
    protected $fillable = ['id', 'descricao', 'quantparc'];
}
