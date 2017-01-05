<?php

namespace Inextends\Tamandua;

class RequiredFieldsChecker
{
    private $errorCodesCollection;

    public function __construct()
    {
        $this->errorCodesCollection = new ErrorCodesCollection();
    }

    /**
     * 
     * @param array $requiredFields
     * @param array $args
     * @return boolean
     * @throws APIException
     */
    public function check($requiredFields, $args = array())
    {
        foreach ($requiredFields as $fieldName => $errorCode) {
            $found = false;
            foreach ($args as $key => $value) {
                if ($key == $fieldName) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                throw new APIException($errorCode);
            }
        }

        return true;
    }
}
