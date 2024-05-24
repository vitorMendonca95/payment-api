<?php

namespace Tests\Feature\Users;

use App\Enums\DocumentTypeEnum;
use App\Helpers\CustomHelper;
use App\Helpers\DocumentHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserCreateTest extends TestCase
{
    use RefreshDatabase;

    const ROUTE = '/api/user/create';

    const DEFAULT_HEADER = [
        'Accept' => 'application/json',
    ];

    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();
    }

    public function testUserCreation(): void
    {
        $password = CustomHelper::generatePassword();

        $response = $this->post(
            self::ROUTE,
            [
                'name' => fake()->name(),
                'email' => fake()->freeEmail(),
                'document' => DocumentHelper::generateCPF(),
                'document_type' => DocumentTypeEnum::Cpf->value,
                'password' => $password,
            ],
            self::DEFAULT_HEADER,
        );

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJsonStructure([
            'data' => [
                'name',
                'email',
                'document',
                'document_type',
                'updated_at',
                'created_at',
                'id',
            ],
        ]);
    }
}
