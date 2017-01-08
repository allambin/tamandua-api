<?php

namespace Inextends\Tamandua;

use Inextends\Tamandua\Models\User;
use Inextends\Tamandua\Models\Project;
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
            case 'code':
                if (!preg_match('#^[\w]+$#', $value)) {
                    throw new APIException(400303);
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
            case 'code':
                $existingProject = Project::where('code', $value)->first();
                if (!empty((array) $existingProject)) {
                    throw new APIException(400304);
                }
            default:
                break;
        }
    }
    
    /**
     * 
     * @param object $object
     * @param \Inextends\Tamandua\Models\User $creator
     * @return boolean
     * @throws APIException
     */
    public static function checkIsCreator($object, $creator) {
        if($object->creator_id != $creator->id) {
            throw new APIException(400104);
        }
    }
}
