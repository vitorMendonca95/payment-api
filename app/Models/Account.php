<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int $user_id
 * @property string $account_type
 * @property float $balance
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property User $user
 */
class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'account_type',
        'balance',
    ];

    protected $casts = [
        'balance' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
