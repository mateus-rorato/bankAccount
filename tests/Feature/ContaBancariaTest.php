<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\ContaBancaria;

class ContaBancariaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_new_account()
    {
        $nome = 'Mateus';
        $saldo = 1000;

        $response = $this->postJson('/api/contas', [
            'nome' => $nome,
            'saldo' => $saldo,
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'nome' => $nome,
                     'saldo' => $saldo,
                 ]);

        $this->assertDatabaseHas('contas_bancarias', [
            'nome' => $nome,
            'saldo' => $saldo,
        ]);
    }

    /** @test */
    public function it_fails_to_create_an_account_with_invalid_data()
    {
        $response = $this->postJson('/api/contas', [
            'nome' => '',
            'saldo' => 'not_a_number',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nome', 'saldo']);
    }

    /** @test */
    public function it_transfers_amount_between_accounts()
    {
        $nomeConta1 = 'Conta 1';
        $nomeConta2 = 'Conta 2';
        $saldoInicialConta1 = 200;
        $saldoInicialConta2 = 300;
        $valorTransferencia = 50;
        
        $fromAccount = ContaBancaria::create(['nome' => $nomeConta1, 'saldo' => $saldoInicialConta1]);
        $toAccount = ContaBancaria::create(['nome' => $nomeConta2, 'saldo' => $saldoInicialConta2]);

        $response = $this->postJson('/api/transfer', [
            'sender' => $fromAccount->id,
            'receiver' => $toAccount->id,
            'amount' => $valorTransferencia,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Transferência realizada com sucesso.']);

        $this->assertEquals(150, $fromAccount->fresh()->saldo);
        $this->assertEquals(350, $toAccount->fresh()->saldo);
    }

    /** @test */
    public function it_fails_transfer_when_insufficient_funds()
    {
        $nomeConta1 = 'Conta 1';
        $nomeConta2 = 'Conta 2';
        $saldoInicialConta1 = 50;
        $saldoInicialConta2 = 300;
        $valorTransferencia = 100;

        $fromAccount = ContaBancaria::create(['nome' => $nomeConta1, 'saldo' => $saldoInicialConta1]);
        $toAccount = ContaBancaria::create(['nome' => $nomeConta2, 'saldo' => $saldoInicialConta2]);

        $response = $this->postJson('/api/transfer', [
            'sender' => $fromAccount->id,
            'receiver' => $toAccount->id,
            'amount' => $valorTransferencia,
        ]);

        $response->assertStatus(400)
                 ->assertJsonFragment(['error' => 'Saldo insuficiente na conta de origem.']);

        $this->assertEquals(50, $fromAccount->fresh()->saldo);
        $this->assertEquals(300, $toAccount->fresh()->saldo);
    }

    /** @test */
    public function it_can_schedule_a_transfer()
    {
        $nomeConta1 = 'Conta 1';
        $nomeConta2 = 'Conta 2';
        $saldoInicialConta1 = 500;
        $saldoInicialConta2 = 400;
        $valorTransferencia = 100;

        $fromAccount = ContaBancaria::create(['nome' => $nomeConta1, 'saldo' => $saldoInicialConta1]);
        $toAccount = ContaBancaria::create(['nome' => $nomeConta2, 'saldo' => $saldoInicialConta2]);

        $response = $this->postJson('/api/agendar', [
            'sender' => $fromAccount->id,
            'receiver' => $toAccount->id,
            'amount' => $valorTransferencia,
            'schedule_date' => now()->addDay()->toDateTimeString(),
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['message' => 'Transferência agendada com sucesso.']);
    }
}
