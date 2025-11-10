<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produtos;
use Illuminate\Http\Request;

class ProdutosControler extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Produtos::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'nome' => 'required',
            'preco' => 'required',
            'foto' => 'required',
        ]);

        $result = Produtos::create($request->all());

        return response()->json($result, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $result = Produtos::findOrFail($id);

        return response()->json($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produtos $produtos)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $id = $request->input('id');
        $result = Produtos::findOrFail($id);
        $result->update($request->all());

        return response()->json($result, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Produtos $produtos)
    {
       $request->validate([
            'id' => 'required'
        ]);

        $id = $request->input('id');
        $result = Produtos::findOrFail($id)->delete();

        return response()->json($result,204);
    }
}
