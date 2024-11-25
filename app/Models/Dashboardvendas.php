<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboardvendas extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'idvendedor';
    public $table = 'dashboard_vendas';
    protected $fillable = ['idvendedor', 'mes', 'ano', 'valor'];
}
