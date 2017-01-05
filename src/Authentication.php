<?php

namespace Inextends\Tamandua;

use Inextends\Tamandua\Models\User;
use Inextends\Tamandua\APIException;

class Authentication
{
    /**
     * 
     * @param string $email
     * @param string $password
     * @return boolean
     * @throws APIException
     */
    public function register($email, $password)
    {
        ErrorHandler::checkFieldValidity('email', $email);
        ErrorHandler::checkFieldUnicity('email', $email);

        $passwordSalt = sha1(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM));
        $hashPassword = password_hash($password, PASSWORD_DEFAULT, ['salt' => $passwordSalt]);

        $user = new User();
        $user->email = $email;
        $user->password = $hashPassword;
        $user->password_salt = $passwordSalt;
        try {
            $user->save();
        } catch (\Illuminate\Database\QueryException $e) {
            throw new APIException(400998, $e->errorInfo[2]);
        }
        
        return true;
    }
}
