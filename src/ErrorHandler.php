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
                break;
            case 'code':
                if (!preg_match('#^[\w]+$#', $value)) {
                    throw new APIException(400303);
                }
                break;
            case 'status':
                if (!in_array($value, array('new', 'assigned', 'pending', 'resolved', 'rejected'))) {
                    throw new APIException(400305);
                }
                break;
            case 'priority':
                if (!in_array($value, array('low', 'normal', 'high', 'urgent'))) {
                    throw new APIException(400306);
                }
                break;
            case 'type':
                if (!in_array($value, array('task', 'issue', 'documentation'))) {
                    throw new APIException(400307);
                }
                break;
            default:
                break;
        }

        return true;
    }

    /**
     * 
     * @param array $names
     * @param array $values
     * @return boolean
     * @throws APIException
     */
    public static function checkFieldsValidity(array $names, array $values)
    {
        foreach ($names as $name) {
            if (!isset($values[$name]) || empty($values[$name])) {
                continue;
            }
            return self::checkFieldValidity($name, $values[$name]);
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
    public static function checkIsCreator($object, $creator)
    {
        if ($object->creator_id != $creator->id) {
            throw new APIException(400104);
        }
    }

    /**
     * 
     * @param string $date
     * @throws APIException
     */
    public static function checkDateValidity($date)
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        if(!$d || ($d && $d->format('Y-m-d') !== $date)) {
            throw new APIException(400308);
        }
        
        return true;
    }
    
    /**
     * 
     * @param string $startDate
     * @param string $endDate
     * @throws APIException
     */
    public static function checkDatesTimeline($startDate, $endDate)
    {
        $startDate = \DateTime::createFromFormat('Y-m-d', $startDate);
        $endDate = \DateTime::createFromFormat('Y-m-d', $endDate);
        
        if($endDate < $startDate) {
            throw new APIException(400309);
        }
    }

}
