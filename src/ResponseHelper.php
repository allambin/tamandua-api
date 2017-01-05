<?php

namespace Inextends\Tamandua;

use Psr\Http\Message\ResponseInterface;

class ResponseHelper
{
    public static function sendJsonResponse(ResponseInterface $response, $data)
    {
        $jsonresponse = $response->withHeader('Content-type', 'application/json; charset=utf-8');
        return $jsonresponse->write(json_encode($data, JSON_PRETTY_PRINT));
    }

    public static function sendJsonErrorResponse(ResponseInterface $response, \Exception $e)
    {
        $jsonresponse = $response->withHeader('Content-type', 'application/json; charset=utf-8');
        $error = new \stdClass();
        $error->status = 401;
        $error->code = $e->getCode();
        $error->message = $e->getMessage();
        return $jsonresponse->write(json_encode($error, JSON_PRETTY_PRINT));
    }
}
