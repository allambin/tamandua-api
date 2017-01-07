<?php

namespace Inextends\Tamandua;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class APIController
{
    public function register(ServerRequestInterface $request, ResponseInterface $response)
    {
        $parsedBody = $request->getParsedBody();

        try {
            $fieldsChecker = new RequiredFieldsChecker();
            $fieldsChecker->check(['email' => 400201, 'password' => 400202], $parsedBody);
            $auth = new Authentication();
            $data = $auth->register($parsedBody['email'], $parsedBody['password']);
            return ResponseHelper::sendJsonResponse($response, $data);
        } catch (\Exception $e) {
            return ResponseHelper::sendJsonErrorResponse($response, $e);
        }
    }
    
    public function login(ServerRequestInterface $request, ResponseInterface $response)
    {
        $parsedBody = $request->getParsedBody();

        try {
            $fieldsChecker = new RequiredFieldsChecker();
            $fieldsChecker->check(['email' => 400201, 'password' => 400202], $parsedBody);
            $auth = new Authentication();
            $data = $auth->login($parsedBody['email'], $parsedBody['password']);
            return ResponseHelper::sendJsonResponse($response, $data);
        } catch (\Exception $e) {
            return ResponseHelper::sendJsonErrorResponse($response, $e);
        }
    }
}
