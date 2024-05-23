<?php

namespace Tests\Feature;

use App\Enums\DocumentType;
use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserCreateFeatureTest extends TestCase
{
    use RefreshDatabase;

    const ROUTE = '/api/user/register';

    protected Generator $faker;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->faker = Faker::create('pt_BR');
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();
    }

    public function testTheApplicationReturnsASuccessfulResponse(): void
    {
        $response = $this->post(
            self::ROUTE,
            [
                'name' => $this->faker->name(),
                'email' => $this->faker->freeEmail(),
                'document' => '61143724097',
                'document_type' => DocumentType::Cpf->value,
                'password' => $this->generatePassword(),
            ],
            [
                'Accept' => 'application/json',
            ]
        );

        $response->assertStatus(Response::HTTP_CREATED);
    }

    protected function generatePassword($length = 12): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
        $password = '';
        $max = strlen($characters) - 1;

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[mt_rand(0, $max)];
        }

        return $password;
    }
}
