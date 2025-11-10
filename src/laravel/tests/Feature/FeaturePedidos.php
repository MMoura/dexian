<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FeaturePedidos extends TestCase
{
    public function VerificaIncluirProduto ()
    {
        $dData = [
            'id_cliente' => '1',
            'id_produto' => '2',
        ];

        $result = $this->post('/pedidos', $data);
        
        $result->assertStatus(201);
    }

    public function ListaTodosOsPedidos()
    {
        $result = $this->get('/pedidos');

        $result->assertStatus(200);
    }

    public function BuscaUmPedido()
    {
        $result = $this->get('/pedido/1');
        $this->assertJson ($result->getContent ());
        
        $result->assertSee ([ 
            'id' => '1'
        ]);
        
        $result->assertOk();
    }

    public function AtualizaDadosPedidos ()
    {
        $dData = [
            "id"=> "1",
            "id_produto"=> "4",
        ];

        $result = $this->patch('/pedidos', $data);
        
        $result->assertStatus(200);

        $result->assertOk();
    }

    public function DeletaDadosPedido ()
    {
        $dData = [
            "id"=> "1"
        ];

        $result = $this->post('pedido/apagar', $data);
        
        $result->assertStatus(204);

        $result->assertOk();
    }
}
