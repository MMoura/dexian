<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clientes;
use Illuminate\Http\Request;

class ClientesControler extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Clientes::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|unique:clientes',
            'telefone' => 'required',
            'data_nasc' => 'required',
            'endereco' => 'required',
            'complemento' => 'required',
            'bairro' => 'required',
            'cep' => 'required',
        ]);

        $result = Clientes::create($request->all());

        return response()->json($result, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $result = Clientes::findOrFail($id);

        return response()->json($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clientes $clientes)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $id = $request->input('id');
        $result = Clientes::findOrFail($id);
        $result->update($request->all());

        return response()->json($result, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Clientes $clientes)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $id = $request->input('id'); //dd($id);
        $result = Clientes::findOrFail($id)->delete();

        return response()->json($result,204);
    }
}
