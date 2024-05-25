<?php

namespace Database\Factories;

use App\Enums\DocumentTypeEnum;
use App\Helpers\DocumentHelper;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'document' => DocumentHelper::generateCPF(),
            'document_type' => DocumentTypeEnum::Cpf->value,
        ];
    }
}
