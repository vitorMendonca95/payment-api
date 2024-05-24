<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * @property integer $id
 * @property string name
 * @property string email
 * @property string document
 * @property string password
 * @property Carbon created_at
 * @property Carbon updated_at
 */

class User extends Model
{
    use HasFactory;
    use Notifiable;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
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
}
