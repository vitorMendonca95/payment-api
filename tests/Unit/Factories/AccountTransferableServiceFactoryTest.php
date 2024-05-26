<?php

namespace Tests\Unit\Factories;

use App\Enums\AccountTypeEnum;
use App\Exceptions\Account\AccountTypeNotFoundException;
use App\Factories\AccountTransferableServiceFactory;
use App\Repositories\Account\AccountRepository;
use App\Repositories\Transaction\TransactionRepository;
use App\Services\Account\AccountCommonService;
use App\Services\ExternalProvider\PaymentAuthorization\CompanyXService;
use App\Services\Transaction\TransactionNotificationService;
use App\Services\User\UserService;
use Exception;
use Illuminate\Support\Facades\App;
use Mockery;
use Tests\TestCase;
use Throwable;

class AccountTransferableServiceFactoryTest extends TestCase
{
    protected TransactionRepository $transactionRepository;
    protected UserService $userService;
    protected CompanyXService $authorizationCompanyService;
    protected AccountCommonService $accountCommonServiceMock;
    protected TransactionNotificationService $transactionNotificationServiceMock;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @throws Throwable
     */
    public function testGetAccountServiceInstance()
    {
        $accountRepository = Mockery::mock(AccountRepository::class);
        App::shouldReceive('make')
            ->with(AccountCommonService::class)
            ->andReturn(new AccountCommonService($accountRepository));

        $factory = new AccountTransferableServiceFactory();
        $serviceInstance = $factory->getAccountServiceInstance(AccountTypeEnum::Common->value);

        $this->assertInstanceOf(AccountCommonService::class, $serviceInstance);
    }

    /**
     * @throws Exception
     */
    public function testGetAccountServiceInstanceTrowingAccountTypeNotFoundException()
    {
        $this->expectException(AccountTypeNotFoundException::class);

        $factory = new AccountTransferableServiceFactory();
        $factory->getAccountServiceInstance(fake()->name());
    }
}
