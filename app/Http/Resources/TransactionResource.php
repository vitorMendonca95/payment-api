<?php

namespace App\Http\Resources;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Transaction
 */
class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'payer_account_id' => $this->payer_account_id,
            'payee_account_id' => $this->payee_account_id,
            'amount' => $this->amount,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'payeeAccount' => $this->payeeAccount,
            'payerAccount' => $this->payerAccount,
        ];
    }
}
