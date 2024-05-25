<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\AccountCreateRequest;
use App\Http\Resources\AccountResource;
use App\Services\Account\AccountService;
use Exception;

class AccountController extends Controller
{
    public function __construct(protected AccountService $accountService)
    {
    }

    /**
     * @throws Exception
     */
    public function create(AccountCreateRequest $request): AccountResource
    {
        $account = $this->accountService->create($request->all());

        return new AccountResource($account);
    }
}
