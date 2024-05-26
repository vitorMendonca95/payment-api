<?php

namespace Tests\Unit\Services\Account;

use App\Exceptions\Account\UserAlreadyHasAnAccountException;
use App\Models\Account;
use App\Repositories\Account\AccountRepository;
use App\Services\Account\AccountService;
use Exception;
use Mockery;
use Tests\TestCase;

class AccountServiceTest extends TestCase
{
    private AccountRepository $accountRepositoryMock;
    protected function setUp(): void
    {
        parent::setUp();

        $this->accountRepositoryMock = Mockery::mock(AccountRepository::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @throws Exception
     */
    public function testCreate()
    {
        $this->accountRepositoryMock
            ->shouldReceive('findByUserId')
            ->andReturnNull();

        $this->accountRepositoryMock
            ->shouldReceive('create')
            ->andReturn(new Account());

        $accountService = new AccountService($this->accountRepositoryMock);

        $account = $accountService->create(['user_id' => 1]);

        $this->assertInstanceOf(Account::class, $account);
    }

    /**
     * @throws Exception
     */
    public function testCreateThrowingUserAlreadyHasAnAccountException()
    {
        $this->expectException(UserAlreadyHasAnAccountException::class);

        $this->accountRepositoryMock
            ->shouldReceive('findByUserId')
            ->andReturn(new Account());

        $this->accountRepositoryMock
            ->shouldReceive('create')
            ->never();

        $accountService = new AccountService($this->accountRepositoryMock);

        $accountService->create(['user_id' => 1]);
    }
}
