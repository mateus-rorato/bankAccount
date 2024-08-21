<?php

namespace App\Services;

use App\Models\ContaBancaria;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ContaBancariaService
{
    public function create(array $dados): ContaBancaria
    {
        return ContaBancaria::create($dados);
    }

    public function transfer(array $dados)
    {
        $fromAccount = ContaBancaria::findOrFail($dados['from_account_id']);
        $toAccount = ContaBancaria::findOrFail($dados['to_account_id']);

        if ($fromAccount->saldo < $dados['amount']) {
            throw new \Exception('Saldo insuficiente na conta de origem.', 400);
        }

        DB::beginTransaction();

        try {
            $fromAccount->saldo -= $dados['amount'];
            $fromAccount->save();

            $toAccount->saldo += $dados['amount'];
            $toAccount->save();

            DB::commit();

            return ['message' => 'Transferência realizada com sucesso.'];
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception('Erro ao realizar a transferência.', 500);
        }
    }
}
