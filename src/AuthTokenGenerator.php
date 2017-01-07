<?php

namespace Inextends\Tamandua;

use Inextends\Tamandua\Models\AuthToken;
use Inextends\Tamandua\APIException;

class AuthTokenGenerator
{
    /**
     * 
     * @param string $user
     * @return AuthToken
     * @throws APIException
     */
    public static function generate($user)
    {
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
        
        return $token;
    }
}
