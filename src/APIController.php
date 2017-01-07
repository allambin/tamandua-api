<?php

namespace Inextends\Tamandua;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class APIController
{
    public static function isTokenValid(ServerRequestInterface $request, ResponseInterface $response)
    {
        $parsedBody = (array) $request->getParsedBody();
        $queryParams = (array) $request->getQueryParams();
        $params = array_merge($parsedBody, $queryParams);

        $fieldsChecker = new RequiredFieldsChecker();
        $fieldsChecker->check(['auth_token' => 400102], $params);
        
        if(!Authentication::isLoggedIn($params['auth_token'])) {
            throw new APIException(400102);
        }
    }
    
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
