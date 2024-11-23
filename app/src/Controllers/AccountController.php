<?php

namespace App\Controllers;

use App\Services\AccountsService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AccountController extends BaseController
{

    public function __construct(private AccountsService $accountsService)
    {
        parent::__construct();
    }
    public function handleRegister(Request $request, Response $response)
    {
        // user_id first_name last_email email password role created_at
        $new_user = $request->getParsedBody();
        $result = $this->accountsService->createAccount($new_user);

        $payload = $this->getPayload($result, 'inserted_account', HTTP_CREATED);

        return $this->renderJson($response, $payload, $payload["status_code"]);
    }
}
