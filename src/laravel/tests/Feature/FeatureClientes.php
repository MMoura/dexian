<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FeatureClientes extends TestCase
{
    public function VerificaValidacaoEmailUnico ()
    {
        $dData = [
            'nome' => 'Nome de Teste',
            'email' => 'mmourajr@gmail.com',
            'telefone' => '11965978879',
            'data_nasc' => '2025-02-1981',
            'endereco' => 'endereco teste',
            'complemento' => 'comp teste',
            'bairro' => 'bairro teste',
            'cep' => '08230167',
        ];

        $result = $this->post('/clientes', $data);
        
        $result->assertStatus(201);
    }

    public function ListaTodosOsClientes()
    {
        $result = $this->get('/clientes');

        $result->assertStatus(200);
    }

    public function BuscaUmClientes()
    {
        $result = $this->get('/cliente/2');
        $this->assertJson ($result->getContent ());
        
        $result->assertSee ([ 
            'id' => '1'
        ]);
        
        $result->assertOk();
    }

    public function AtualizaDadosCliente ()
    {
        $dData = [
            "id"=> "1",
            "nome"=> "Mauricio Moura",
            "email"=> "mmourajr@gmail",
            "data_nasc"=> "1981-02-16",
            "telefone"=> "1196597-8879",
            "endereco"=> "Rua Particular B,23 - updated",
            "complemento"=> "Travessa da Rua BB Varela",
            "bairro"=> "Vila Taquari",
            "cep"=> "08240-167"
        ];

        $result = $this->patch('/clientes', $data);
        
        $result->assertStatus(200);

        $result->assertOk();
    }

    public function DeletaDadosCliente ()
    {
        $dData = [
            "id"=> "1"
        ];

        $result = $this->post('cliente/apagar', $data);
        
        $result->assertStatus(204);

        $result->assertOk();
    }


}
