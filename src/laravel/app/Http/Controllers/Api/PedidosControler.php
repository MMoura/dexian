<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedidos;
use Illuminate\Http\Request;

class PedidosControler extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Pedidos::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_cliente' => 'required',
            'id_produto' => 'required',
        ]);

        $result = Pedidos::create($request->all());

        return response()->json($result, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $result = Pedidos::findOrFail($id);

        return response()->json($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedidos $pedidos)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $id = $request->input('id');
        $result = Pedidos::findOrFail($id);
        $result->update($request->all());

        return response()->json($result, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,Pedidos $pedidos)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $id = $request->input('id');
        $result = Pedidos::findOrFail($id)->delete();

        return response()->json($result,204);
    }
}
