<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FeatureProdutos extends TestCase
{
    public function VerificaIncluirProduto ()
    {
        $dData = [
            'nome' => 'Produto de Teste',
            'preco' => '3.99',
            'foto' => 'caminho foto teste',
        ];

        $result = $this->post('/produtos', $data);
        
        $result->assertStatus(201);
    }

    public function ListaTodosOsProdutos()
    {
        $result = $this->get('/produtos');

        $result->assertStatus(200);
    }

    public function BuscaUmProduto()
    {
        $result = $this->get('/produto/1');
        $this->assertJson ($result->getContent ());
        
        $result->assertSee ([ 
            'id' => '1'
        ]);
        
        $result->assertOk();
    }

    public function AtualizaDadosProduto ()
    {
        $dData = [
            "id"=> "1",
            "preco"=> "14.00",
        ];

        $result = $this->patch('/produtos', $data);
        
        $result->assertStatus(200);

        $result->assertOk();
    }

    public function DeletaDadosProduto ()
    {
        $dData = [
            "id"=> "1"
        ];

        $result = $this->post('produto/apagar', $data);
        
        $result->assertStatus(204);

        $result->assertOk();
    }
}
