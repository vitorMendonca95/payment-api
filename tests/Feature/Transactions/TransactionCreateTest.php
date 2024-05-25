<?php

namespace Tests\Feature\Transactions;

use App\Enums\AccountTypeEnum;
use App\Enums\TransactionStatusEnum;
use App\Exceptions\Transaction\AccountTransferNotAllowedException;
use App\Exceptions\Transaction\InsufficientFundsException;
use App\Interfaces\AuthorizationCompanyServiceInterface;
use App\Models\Account;
use App\Models\User;
use App\Services\ExternalProvider\AuthorizationCompany\AuthorizationCompanyService;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\Stub\AuthorizationCompanyServiceStub;
use Tests\TestCase;

class TransactionCreateTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = '/api/transfer';

    const DEFAULT_HEADER = [
        'Accept' => 'application/json',
    ];

    protected Generator $faker;
    protected User $userPayee;
    protected User $userPayer;
    protected Account $account;

    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->refreshApplication();

        $this->app->bind(AuthorizationCompanyServiceInterface::class, AuthorizationCompanyServiceStub::class);

        $this->userPayee = User::factory()->create();
        $this->userPayer = User::factory()->create();
    }

    protected function tearDown(): void
    {
        $this->app->bind(AuthorizationCompanyServiceInterface::class, AuthorizationCompanyService::class);
        parent::tearDown();
    }

    public function testTransactionCreation(): void
    {
        $accountPayer = Account::factory()->create([
            'user_id' => $this->userPayer->id,
            'account_type' => AccountTypeEnum::Common->value,
            'balance' => 2000.00,
        ]);

        $accountPayee = Account::factory()->create([
            'user_id' => $this->userPayee->id,
            'account_type' => AccountTypeEnum::Common->value,
            'balance' => 1000.00,
        ]);

        $amountToTransfer = 500;
        $response = $this->post(
            self::ROUTE,
            [
                'value' => $amountToTransfer,
                'payer' => $this->userPayer->id,
                'payee' => $this->userPayee->id,
            ],
            self::DEFAULT_HEADER,
        );

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'status',
                'payer_account_id',
                'payee_account_id',
                'amount',
                'updated_at',
                'created_at',
                'payeeAccount' => [
                    'id',
                    'user_id',
                    'account_type',
                    'balance',
                    'created_at',
                    'updated_at',
                ],
                'payerAccount' => [
                    'id',
                    'user_id',
                    'account_type',
                    'balance',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);

        $this->assertEquals(TransactionStatusEnum::Completed->value, $response->json('data.status'));

        $this->assertEquals($accountPayer->id, $response->json('data.payer_account_id'));
        $this->assertEquals($accountPayee->id, $response->json('data.payee_account_id'));

        $expectedPayerBalance = $accountPayer->balance - $amountToTransfer;
        $expectedPayeeBalance = $accountPayee->balance + $amountToTransfer;

        $this->assertEquals($expectedPayerBalance, $response->json('data.payerAccount.balance'));
        $this->assertEquals($expectedPayeeBalance, $response->json('data.payeeAccount.balance'));
    }

    public function testTransactionCreationWithMerchantAsPayer(): void
    {
        Account::factory()->create([
            'user_id' => $this->userPayer->id,
            'account_type' => AccountTypeEnum::Merchant->value,
            'balance' => 2000.00,
        ]);

        Account::factory()->create([
            'user_id' => $this->userPayee->id,
            'account_type' => AccountTypeEnum::Common->value,
            'balance' => 1000.00,
        ]);

        $amountToTransfer = 500;
        $response = $this->post(
            self::ROUTE,
            [
                'value' => $amountToTransfer,
                'payer' => $this->userPayer->id,
                'payee' => $this->userPayee->id,
            ],
            self::DEFAULT_HEADER,
        );

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        $this->assertEquals('merchant accounts are not allowed to transfer money', $response->json('message'));
        $this->assertEquals(AccountTransferNotAllowedException::class, $response->json('exception'));
    }

    public function testTransactionCreationWithInsufficientFundsInAccount(): void
    {
        Account::factory()->create([
            'user_id' => $this->userPayer->id,
            'account_type' => AccountTypeEnum::Common->value,
            'balance' => 50.00,
        ]);

        Account::factory()->create([
            'user_id' => $this->userPayee->id,
            'account_type' => AccountTypeEnum::Merchant->value,
            'balance' => 1000.00,
        ]);

        $amountToTransfer = 500;
        $response = $this->post(
            self::ROUTE,
            [
                'value' => $amountToTransfer,
                'payer' => $this->userPayer->id,
                'payee' => $this->userPayee->id,
            ],
            self::DEFAULT_HEADER,
        );

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        $this->assertEquals('Payer does not have enough funds to complete the transaction', $response->json('message'));
        $this->assertEquals(InsufficientFundsException::class, $response->json('exception'));
    }
}
