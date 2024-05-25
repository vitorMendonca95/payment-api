<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionCreateRequest;
use App\Http\Resources\TransactionResource;
use App\Services\Transaction\TransactionService;
use Throwable;

class TransactionController extends Controller
{
    public function __construct(protected TransactionService $transactionService)
    {
    }

    /**
     * @throws Throwable
     */
    public function transfer(TransactionCreateRequest $request): TransactionResource
    {
        $transaction = $this->transactionService->transfer($request->all());

        return new TransactionResource($transaction);
    }
}
