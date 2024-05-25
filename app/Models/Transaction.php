<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $payer_account_id
 * @property int $payee_account_id
 * @property string $amount
 * @property string|null $description
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Account $payeeAccount
 * @property Account $payerAccount
 * @method static create()
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payer_account_id',
        'payee_account_id',
        'amount',
        'description',
        'status',
    ];

    public function payerAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'payer_account_id', 'id');
    }

    public function payeeAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'payee_account_id', 'id');
    }
}
