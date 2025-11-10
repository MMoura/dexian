<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produtos extends Model
{
    use SoftDeletes;
    protected $fillable = ['id', 'nome', 'preco', 'foto'];
}
