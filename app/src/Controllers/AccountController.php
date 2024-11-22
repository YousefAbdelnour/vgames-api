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
        $result = $this->accountsService->createUpdate($new_user);
        $status = $result->isSuccess() ? HTTP_CREATED : 400;

        if ($result->isSuccess()) {
            $payload['status'] = $status;
            $payload['success'] = true;
            $payload['inserted_account'] = $result->getData();
        } else {
            $payload['status'] = $status;
            $payload['success'] = false;
            $payload['errors'] = $result->getData();
        }

        $payload['message'] = $result->getMessage();
        return $this->renderJson($response, $payload, $status);
    }
}
