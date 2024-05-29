<?php

namespace App\Services\Transaction;

use App\Enums\AccountTypeEnum;
use App\Enums\TransactionStatusEnum;
use App\Exceptions\Transaction\AccountTransferNotAllowedException;
use App\Exceptions\Transaction\PayerOrPayeeNotFoundException;
use App\Exceptions\Transaction\UnauthorizedTransactionException;
use App\Factories\AccountTransferableServiceFactory;
use App\Factories\ExternalProvider\PaymentAuthorization\PaymentAuthorizationServiceFactory;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Transaction\TransactionRepository;
use App\Services\User\UserService;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class TransactionService
{
    public function __construct(
        protected TransactionRepository $transactionRepository,
        protected UserService $userService,
        protected AccountTransferableServiceFactory $accountTransferableServiceFactory,
        protected PaymentAuthorizationServiceFactory $paymentAuthorizationServiceFactory,
        protected TransactionNotificationService $transactionNotificationService,
    ) {
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function transfer(array $transactionData): Transaction
    {
        $payeeUser = $this->userService->retrieveUser($transactionData['payee']);
        $payerUser = $this->userService->retrieveUser($transactionData['payer']);

        $this->validateIfBothUsersExists($payeeUser, $payerUser);

        $payerAccount = $payerUser->getAccount();
        $payeeAccount = $payeeUser->getAccount();

        $transactionData = $this->parseTransactionData($payerAccount, $payeeAccount, $transactionData);

        $transaction = $this->transactionRepository->create([
            'status' => TransactionStatusEnum::Pending->value,
            ...$transactionData,
        ]);

        DB::beginTransaction();

        try {
            $this->processTransfer($payerAccount, $payeeAccount, $transactionData['amount']);
            $this->transactionRepository->updateStatus(TransactionStatusEnum::Completed);

            $this->transactionNotificationService->sendNotificationToPayee($payeeUser, $transaction);
        } catch (Throwable $throwable) {
            DB::rollBack();

            $this->transactionRepository->updateStatus(TransactionStatusEnum::Failed);

            throw $throwable;
        }

        DB::commit();

        return $transaction;
    }


    /**
     * @throws PayerOrPayeeNotFoundException
     */
    private function validateIfBothUsersExists(?User $payeeUser, ?User $payerUser): void
    {
        if (empty($payeeUser) || empty($payerUser)) {
            throw new PayerOrPayeeNotFoundException();
        }
    }

    /**
     * @throws AccountTransferNotAllowedException
     */
    private function validateIfPayerIsAbleToTransferFunds(Account $account): void
    {
        if (!AccountTypeEnum::accountTypeCanTransferFunds($account->account_type)) {
            throw new AccountTransferNotAllowedException($account->account_type);
        }
    }

    /**
     * @throws UnauthorizedTransactionException
     */
    private function checkAuthorizationService(Account $account): void
    {
        try {
            $authorizationCompanyAdapter = $this->paymentAuthorizationServiceFactory->getAuthorizationService();
            $authorizationCompanyAdapter->authorize($account->toArray());
        } catch (Throwable) {
            throw new UnauthorizedTransactionException();
        }
    }

    /**
     * @throws AccountTransferNotAllowedException
     * @throws UnauthorizedTransactionException
     * @throws Exception
     */
    private function processTransfer($payerAccount, $payeeAccount, $amountToTransfer): void
    {
        $this->validateTransaction($payerAccount);

        $accountService = $this->accountTransferableServiceFactory
            ->getAccountServiceInstance($payerAccount->account_type);

        $accountService->transferFunds($payerAccount, $payeeAccount, $amountToTransfer);
    }

    /**
     * @throws AccountTransferNotAllowedException
     * @throws UnauthorizedTransactionException
     */
    private function validateTransaction(Account $payerAccount): void
    {
        $this->validateIfPayerIsAbleToTransferFunds($payerAccount);
        $this->checkAuthorizationService($payerAccount);
    }

    /**
     * @param Account $payerAccount
     * @param Account $payeeAccount
     * @param array $transactionData
     * @return array
     */
    private function parseTransactionData(Account $payerAccount, Account $payeeAccount, array $transactionData): array
    {
        return [
            'payer_account_id' => $payerAccount->id,
            'payee_account_id' => $payeeAccount->id,
            'amount' => $transactionData['value']
        ];
    }
}
