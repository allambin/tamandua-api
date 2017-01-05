<?php

namespace Inextends\Tamandua;

use Inextends\Tamandua\Models\User;
use Inextends\Tamandua\APIException;

class ErrorHandler
{
    /**
     * 
     * @param string $name
     * @param string $value
     * @throws APIException
     */
    public static function checkFieldValidity($name, $value)
    {
        switch ($name) {
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    throw new APIException(400301);
                }
            default:
                break;
        }
    }

    /**
     * 
     * @param string $name
     * @param string $value
     * @throws APIException
     */
    public static function checkFieldUnicity($name, $value)
    {
        switch ($name) {
            case 'email':
                $existingUser = User::where('email', $value)->first();
                if (!empty((array) $existingUser)) {
                    throw new APIException(400302);
                }
            default:
                break;
        }
    }
}
