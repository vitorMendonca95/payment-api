<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $account_type
 * @property double $amount
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_type',
        'amount',
    ];
}
