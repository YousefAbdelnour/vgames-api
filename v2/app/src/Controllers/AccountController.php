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
        $user_data = $request->getParsedBody();

        $issued_at = time();
        $expires_at = $issued_at + (60 * 60); //token valid for an hour
        $register_claims = [
            'iss' => 'http://localhost/vgames-api',
            'aud' => 'http://videogames.com',
            'iat' => $issued_at,
            'exp' => $expires_at,
        ];

        $private_claims = [
            "user_id" => $user_data['user_id'],
            "email" => $user_data['email'],
            "role" => $user_data['role'],
        ];

        $payload = array_merge($register_claims, $private_claims);

        $jwt = JWT::encode($payload, $key, 'HS256');

        return [
            "status" => "success",
            "message" => "Token generated successfully",
            "token" => $jwt,
        ];
    }


    public function handleLogin(Request $request, Response $response)
    {
        $existing_user = $request->getParsedBody();

        $result = $this->accountsService->loginToAccount($existing_user);

        //if the returned info is successful; proceed with token generation and data retrieval
        if ($result->isSuccess()) {

            $data = $result->getData();

            //avoiding displaying hashed password
            $displayed_data['email'] = $data['email'];
            $displayed_data['role'] = $data['role'];

            $message = $result->getMessage();

            $request = $request->withParsedBody($data);
            //use the generate token method to generate a login token
            $jwt_data = $this->handleGenerateToken($request, $response);

            //preparing the payload
            $payload = [
                "status" => "success",
                "message" => $message,
                "data" => $displayed_data,
                "token" => $jwt_data['token'],
            ];
            //return renderJson with the prepared payload and the happy status code
            return $this->renderJson($response, $payload, status_code: 200);
        }
        //
        return $this->renderJson($response, [
            "status" => "error",
            "message" => "Invalid credentials",
        ], HTTP_BAD_REQUEST);
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
