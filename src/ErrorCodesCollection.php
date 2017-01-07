<?php

namespace Inextends\Tamandua;

class ErrorCodesCollection
{
    private $errors = [
        400101 => 'User not authenticated',
        400102 => 'Unauthorized action',
        400103 => 'Login failed',
        400201 => 'Email field is missing',
        400202 => 'Password field is missing',
        400301 => 'Email field is invalid',
        400302 => 'Email is already taken',
        400998 => 'SQL error',
        400999 => 'Unknown error',
    ];

    /**
     * 
     * @param string|int $code
     * @return boolean
     */
    public function getError($code)
    {
        if (isset($this->errors[$code])) {
            return $this->errors[$code];
        }

        return false;
    }
}
