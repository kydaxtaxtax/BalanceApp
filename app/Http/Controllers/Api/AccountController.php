<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AccountRepository;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function index(Request $request)
    {
        $userId = auth()->id();
        $accountData = $this->accountRepository->getAccountData($userId);

        return response()->json($accountData);
    }
}
