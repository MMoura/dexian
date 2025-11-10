<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedidos extends Model
{
    use SoftDeletes;
    protected $fillable = ['id', 'id_cliente', 'id_produto'];
}
