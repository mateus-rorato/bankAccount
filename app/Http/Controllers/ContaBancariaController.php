<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContaBancariaStoreRequest;
use App\Http\Requests\ContaBancariaTransferRequest;
use Illuminate\Http\Request;
use App\Models\ContaBancaria;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Services\ContaBancariaService;
use App\Services\TransferAuthorizationService;
use App\Jobs\ProcessTransfer;

class ContaBancariaController extends Controller
{
    protected $authorizationService;

    public function __construct(TransferAuthorizationService $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    public function store(ContaBancariaStoreRequest $request, ContaBancariaService $contaBancariaService)
    {
        $conta = $contaBancariaService->create($request->validated());
        return response()->json($conta, 201);
    }

    public function transfer(ContaBancariaTransferRequest $request, ContaBancariaService $contaBancariaService)
    {
        try 
        {
            $isAuthorized = $this->authorizationService->authorizeTransfer($request->fromAccountId, $request->toAccountId, $request->amount);

            if (!$isAuthorized) {
                return response()->json(['error' => 'Transferência não autorizada.'], 403);
            }
            
            $result = $contaBancariaService->transfer($request->validated());
            return response()->json($result, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function scheduled(ContaBancariaTransferRequest $request)
    {
        $fromAccountId = $request->input('from_account_id');
        $toAccountId = $request->input('to_account_id');
        $amount = $request->input('amount');

        ProcessTransfer::dispatch($fromAccountId, $toAccountId, $amount);

        return response()->json(['message' => 'Transferência agendada com sucesso.']);
    }

    public function index(Request $request)
    {
        try {
            $accountId = $request->query('account_id');

            if ($accountId) {
                $conta = ContaBancaria::findOrFail($accountId);
                return response()->json($conta, 200);
            } else {
                $contas = ContaBancaria::all();
                return response()->json($contas, 200);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Conta não encontrada.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar contas.'], 500);
        }
    }
}
