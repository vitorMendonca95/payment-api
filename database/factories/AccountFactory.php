<?php

namespace Database\Factories;

use App\Enums\AccountTypeEnum;
use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $accountTypes = array_column(AccountTypeEnum::cases(), 'value');

        return [
            'user_id' => User::factory()->create()->id,
            'account_type' => fake()->randomElement($accountTypes),
            'balance' => fake()->numberBetween(1000, 10000),
        ];
    }
}
