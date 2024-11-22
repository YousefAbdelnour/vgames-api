<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use App\Services\AccountsService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AccountController extends BaseController
{

    public function __construct(private AccountsService $accountsService)
    {
        parent::__construct();
    }

    public function handleGenerateToken(Request $request, Response $response)
    {
        $key = SECRET_KEY;

        //TODO: query the DB to ensure that the user is who they claim to be? Meaning, they need to
        //have a valid account  based on the provided credentials

        //* Assuming that the app/user was successfully logged in
        //! Generate a token containing the following private claims
        $issued_at = time();
        $expires_at = $issued_at + (60*60);
        $register_claims = [
            'iss' => 'http://localhost/worldcup-api',
            'aud' => 'http://myson.com',
            'iat' => $issued_at,
            'exp' => $expires_at
        ];

        $private_claims = array(
            "user_id" => 1,
            "email" => "",
            "role" => "admin",
        );

        $payload = array_merge($register_claims, $private_claims);
        /**
         * IMPORTANT:
         * You must specify supported algorithms for your application. See
         * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
         * for a list of spec-compliant algorithms.
         */
        $jwt = JWT::encode($payload, SECRET_KEY, 'HS256');

        $jwt_data = [
            "status" => "success",
            "message" => "The token has been generated",
            "token" => $jwt
        ];

        return $this->renderJson($response, $jwt_data);
    }


    public function handleRegister(Request $request, Response $response)
    {
        // user_id first_name last_email email password role created_at
        $new_user = $request->getParsedBody();
        $result = $this->accountsService->createAccount($new_user);
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
