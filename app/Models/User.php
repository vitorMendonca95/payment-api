<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $document
 * @property string $document_type
 * @property mixed $password
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Account $account
 * @method static find(int $int)
 */

class User extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'document',
        'document_type',
        'password',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function account(): HasOne
    {
        return $this->hasOne(Account::class, 'user_id', 'id');
    }

    public function getAccount(): Account
    {
        return $this->account;
    }
}
