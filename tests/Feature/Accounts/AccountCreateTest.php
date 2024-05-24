<?php

namespace Tests\Feature\Accounts;

use App\Enums\AccountTypeEnum;
use App\Models\User;
use Faker\Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AccountCreateTest extends TestCase
{
    use RefreshDatabase;

    const ROUTE = '/api/account/create';

    const DEFAULT_HEADER = [
        'Accept' => 'application/json',
    ];

    protected Generator $faker;
    protected User $user;

    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();

        $this->user = User::factory()->create();
    }

    public function testUserCreation(): void
    {
        $amount = fake()->randomFloat(2, 1000, 10000);
        $accountType = fake()->randomElement([
            AccountTypeEnum::Common->value,
            AccountTypeEnum::Merchant->value,
        ]);

        $response = $this->post(
            self::ROUTE,
            [
                'user_id' => $this->user->id,
                'account_type' => $accountType,
                'amount' => $amount,
            ],
            self::DEFAULT_HEADER,
        );

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'user_id',
                'account_type',
                'amount',
                'updated_at',
                'created_at',
                'id',
            ],
        ])
        ->assertJson([
            'data' => [
                'user_id' => $this->user->id,
                'account_type' => $accountType,
                'amount' => $amount,
            ],
        ]);
    }
}
