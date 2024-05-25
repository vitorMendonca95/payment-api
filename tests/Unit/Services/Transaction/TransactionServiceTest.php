<?php

namespace Tests\Unit\Services\Transaction;

use App\Enums\AccountTypeEnum;
use App\Exceptions\Transaction\AccountTransferNotAllowedException;
use App\Exceptions\Transaction\PayerOrPayeeNotFoundException;
use App\Exceptions\Transaction\UnauthorizedTransactionException;
use App\Factory\AccountTransferableServiceFactory;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Transaction\TransactionRepository;
use App\Services\Account\AccountCommonService;
use App\Services\ExternalProvider\AuthorizationCompany\AuthorizationCompanyService;
use App\Services\Transaction\TransactionNotificationService;
use App\Services\Transaction\TransactionService;
use App\Services\User\UserService;
use Exception;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;
use Throwable;

class TransactionServiceTest extends TestCase
{
    protected TransactionRepository $transactionRepository;
    protected UserService $userService;
    protected AuthorizationCompanyService $authorizationCompanyService;
    protected AccountCommonService $accountCommonServiceMock;
    protected TransactionNotificationService $transactionNotificationServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->transactionRepository = Mockery::mock(TransactionRepository::class);
        $this->userService = Mockery::mock(UserService::class);
        $this->userMock = Mockery::mock(User::class);
        $this->authorizationCompanyService = Mockery::mock(AuthorizationCompanyService::class);
        $this->accountCommonServiceMock = Mockery::mock(AccountCommonService::class);
        $this->accountTransferableServiceFactoryMock = Mockery::mock(AccountTransferableServiceFactory::class);
        $this->transactionNotificationServiceMock = Mockery::mock(TransactionNotificationService::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @throws Throwable
     */
    public function testTransfer()
    {
        $transactionData = [
            'payer' => 1,
            'payee' => 2,
            'value' => 200
        ];

        $this->userService->shouldReceive('retrieveUser')->with(1)->andReturn($this->userMock);
        $this->userService->shouldReceive('retrieveUser')->with(2)->andReturn($this->userMock);

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $this->userMock->shouldReceive('getAccount')->andReturns(
            new Account(['user_id' => 1, 'account_type' => AccountTypeEnum::Common->value, 'balance' => 2000.00]),
            new Account(['user_id' => 2, 'account_type' => AccountTypeEnum::Common->value, 'balance' => 2000.00])
        );

        $this->transactionRepository->shouldReceive('create')->andReturn(new Transaction());

        $this->authorizationCompanyService->shouldReceive('authorize')->once()->andReturn(true);

        $this->transactionRepository->shouldReceive('updateStatus')->once();
        $this->accountCommonServiceMock->shouldReceive('transferFunds')->andReturnNull();
        $this->accountTransferableServiceFactoryMock
            ->shouldReceive('getAccountServiceInstance')
            ->andReturn($this->accountCommonServiceMock);

        $this->transactionNotificationServiceMock->shouldReceive('sendNotificationToPayee')->andReturnNull();

        $service = new TransactionService(
            $this->transactionRepository,
            $this->userService,
            $this->authorizationCompanyService,
            $this->accountTransferableServiceFactoryMock,
            $this->transactionNotificationServiceMock,
        );

        $transaction = $service->transfer($transactionData);

        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    /**
     * @throws Throwable
     */
    public function testTransferAccountTransferNotAllowedException()
    {
        $transactionData = [
            'payer' => 1,
            'payee' => 2,
            'value' => 100
        ];

        $this->userService->shouldReceive('retrieveUser')->with(1)->andReturn($this->userMock);
        $this->userService->shouldReceive('retrieveUser')->with(2)->andReturn($this->userMock);

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollback')->once();

        $this->transactionRepository->shouldReceive('updateStatus')->once();

        $this->userMock->shouldReceive('getAccount')->andReturns(
            new Account(['user_id' => 1, 'account_type' => AccountTypeEnum::Merchant->value, 'balance' => 2000.00]),
            new Account(['user_id' => 2, 'account_type' => AccountTypeEnum::Common->value, 'balance' => 2000.00])
        );

        $this->transactionRepository->shouldReceive('create')->andReturn(new Transaction());

        $service = new TransactionService(
            $this->transactionRepository,
            $this->userService,
            $this->authorizationCompanyService,
            $this->accountTransferableServiceFactoryMock,
            $this->transactionNotificationServiceMock,
        );

        $this->expectException(AccountTransferNotAllowedException::class);
        $service->transfer($transactionData);
    }

    /**
     * @throws Throwable
     */
    public function testTransferUnauthorizedTransactionException()
    {
        $transactionData = [
            'payer' => 1,
            'payee' => 2,
            'value' => 100
        ];

        $this->userService->shouldReceive('retrieveUser')->with(1)->andReturn($this->userMock);
        $this->userService->shouldReceive('retrieveUser')->with(2)->andReturn($this->userMock);

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollback')->once();

        $this->transactionRepository->shouldReceive('updateStatus')->once();

        $this->userMock->shouldReceive('getAccount')->andReturns(
            new Account(['user_id' => 1, 'account_type' => AccountTypeEnum::Common->value, 'balance' => 2000.00]),
            new Account(['user_id' => 2, 'account_type' => AccountTypeEnum::Common->value, 'balance' => 2000.00])
        );

        $this->transactionRepository->shouldReceive('create')->andReturn(new Transaction());
        $this->authorizationCompanyService->shouldReceive('authorize')->andThrows(new Exception());

        $service = new TransactionService(
            $this->transactionRepository,
            $this->userService,
            $this->authorizationCompanyService,
            $this->accountTransferableServiceFactoryMock,
            $this->transactionNotificationServiceMock,
        );

        $this->expectException(UnauthorizedTransactionException::class);
        $service->transfer($transactionData);
    }

    /**
     * @throws Throwable
     */
    public function testTransferWithPayerOrPayeeNotFoundException()
    {
        $transactionData = [
            'payer' => 1,
            'payee' => 2,
            'value' => 100
        ];

        $this->userService->shouldReceive('retrieveUser')->with(1)->andReturn($this->userMock);
        $this->userService->shouldReceive('retrieveUser')->with(2)->andReturnNull();

        $service = new TransactionService(
            $this->transactionRepository,
            $this->userService,
            $this->authorizationCompanyService,
            $this->accountTransferableServiceFactoryMock,
            $this->transactionNotificationServiceMock,
        );

        $this->expectException(PayerOrPayeeNotFoundException::class);
        $service->transfer($transactionData);
    }
}
