<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ClientesControler;
use App\Http\Controllers\Api\ProdutosControler;
use App\Http\Controllers\Api\PedidosControler;
 

Route::get('/clientes', [ClientesControler::class, 'index']);
Route::get('/cliente/{id}', [ClientesControler::class, 'show']);
Route::post('/clientes', [ClientesControler::class, 'store']);
Route::patch('/clientes', [ClientesControler::class, 'update']);
Route::post('/cliente/apagar', [ClientesControler::class, 'destroy']);

Route::get('/produtos', [ProdutosControler::class, 'index']);
Route::get('/produto/{id}', [ProdutosControler::class, 'show']);
Route::post('/produtos', [ProdutosControler::class, 'store']);
Route::patch('/produtos', [ProdutosControler::class, 'update']);
Route::post('/produto/apagar', [ProdutosControler::class, 'destroy']);


Route::get('/pedidos', [PedidosControler::class, 'index']);
Route::get('/pedido/{id}', [PedidosControler::class, 'show']);
Route::post('/pedidos', [PedidosControler::class, 'store']);
Route::patch('/pedidos', [PedidosControler::class, 'update']);
Route::post('/pedido/apagar', [PedidosControler::class, 'destroy']);

