<?php

namespace Inextends\Tamandua;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class APIController
{
    public static function isTokenValid(ServerRequestInterface $request, ResponseInterface $response)
    {
        $queryParams = (array) $request->getQueryParams();

        $fieldsChecker = new RequiredFieldsChecker();
        $fieldsChecker->check(['auth_token' => 400102], $queryParams);
        
        if(!Authentication::isLoggedIn($queryParams['auth_token'])) {
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
    
    public function createProject(ServerRequestInterface $request, ResponseInterface $response)
    {
        $parsedBody = $request->getParsedBody();
        $queryParams = (array) $request->getQueryParams();
        $user = UserRepository::getCurrentUserFromToken($queryParams['auth_token']);

        try {
            $fieldsChecker = new RequiredFieldsChecker();
            $fieldsChecker->check(['code' => 400203, 'title' => 400204], $parsedBody);
            $projectRepo = new ProjectRespository();
            $data = $projectRepo->create($parsedBody['code'], $parsedBody['title'], $user, $parsedBody);
            return ResponseHelper::sendJsonResponse($response, $data);
        } catch (\Exception $e) {
            return ResponseHelper::sendJsonErrorResponse($response, $e);
        }
    }
    
    public function updateProject(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $parsedBody = $request->getParsedBody();
        $queryParams = (array) $request->getQueryParams();
        $user = UserRepository::getCurrentUserFromToken($queryParams['auth_token']);

        try {
            $fieldsChecker = new RequiredFieldsChecker();
            $fieldsChecker->check(['id' => 400205], $args);
            $projectRepo = new ProjectRespository();
            $data = $projectRepo->update($args['id'], $user, $parsedBody);
            return ResponseHelper::sendJsonResponse($response, $data);
        } catch (\Exception $e) {
            return ResponseHelper::sendJsonErrorResponse($response, $e);
        }
    }
    
    public function deleteProject(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $parsedBody = $request->getParsedBody();
        $queryParams = (array) $request->getQueryParams();
        $user = UserRepository::getCurrentUserFromToken($queryParams['auth_token']);

        try {
            $fieldsChecker = new RequiredFieldsChecker();
            $fieldsChecker->check(['id' => 400205], $args);
            $projectRepo = new ProjectRespository();
            $data = $projectRepo->delete($args['id'], $user);
            return ResponseHelper::sendJsonResponse($response, $data);
        } catch (\Exception $e) {
            return ResponseHelper::sendJsonErrorResponse($response, $e);
        }
    }
    
    public function getProjects(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        try {
            $projectRepo = new ProjectRespository();
            $data = $projectRepo->getAll();
            return ResponseHelper::sendJsonResponse($response, $data);
        } catch (\Exception $e) {
            return ResponseHelper::sendJsonErrorResponse($response, $e);
        }
    }
    
    public function getProject(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        try {
            $fieldsChecker = new RequiredFieldsChecker();
            $fieldsChecker->check(['id' => 400205], $args);
            $projectRepo = new ProjectRespository();
            $data = $projectRepo->get($args['id']);
            return ResponseHelper::sendJsonResponse($response, $data);
        } catch (\Exception $e) {
            return ResponseHelper::sendJsonErrorResponse($response, $e);
        }
    }
    
    public function getUsers(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        try {
            $userRepo = new UserRepository();
            $data = $userRepo->getAll();
            return ResponseHelper::sendJsonResponse($response, $data);
        } catch (\Exception $e) {
            return ResponseHelper::sendJsonErrorResponse($response, $e);
        }
    }
    
    public function getUser(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        try {
            $fieldsChecker = new RequiredFieldsChecker();
            $fieldsChecker->check(['id' => 400205], $args);
            $userRepo = new UserRepository();
            $data = $userRepo->get($args['id']);
            return ResponseHelper::sendJsonResponse($response, $data);
        } catch (\Exception $e) {
            return ResponseHelper::sendJsonErrorResponse($response, $e);
        }
    }
}
