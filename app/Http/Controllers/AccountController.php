<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\AccountCreateRequest;
use App\Http\Resources\AccountResource;
use App\Services\Account\AccountService;

class AccountController extends Controller
{
    public function __construct(protected AccountService $accountService)
    {
    }

    public function create(AccountCreateRequest $request): AccountResource
    {
        $account = $this->accountService->create($request->all());

        return new AccountResource($account);
    }
}
