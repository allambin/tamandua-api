<?php

namespace Inextends\Tamandua;

use Inextends\Tamandua\Models\User;
use Inextends\Tamandua\Models\AuthToken;
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
    
    /**
     * 
     * @param string $email
     * @param string $password
     * @return \stdClass
     * @throws APIException
     */
    public function login($email, $password)
    {
        $user = User::where('email', $email)->first();
        if (empty((array) $user)) {
            throw new APIException(400103);
        }
        
        $hashPassword = password_hash($password, PASSWORD_DEFAULT, ['salt' => $user->password_salt]);
        if($hashPassword == $user->password) {
            $token = new AuthToken();
            $token->token = sha1($user->email . time());
            $d = new \DateTime();
            $d->setTimezone(new \DateTimeZone('Europe/Brussels'));
            $d->modify('+2 hours');
            $token->valid_until = $d->format('y-m-d H:i:s');
            $token->user_id = $user->id;
            try {
                $token->save();
                AuthToken::where('user_id', $user->id)->where('token', '<>', $token->token)->delete();
            } catch (\Illuminate\Database\QueryException $e) {
                throw new APIException(400998, $e->errorInfo[2]);
            }
        } else {
            throw new APIException(400103);
        }
        
        $message = new \stdClass();
        $message->auth_token = $token->token;
        return $message;
    }
}
