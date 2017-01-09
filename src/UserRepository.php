<?php

namespace Inextends\Tamandua;

use Inextends\Tamandua\Models\User;

class UserRepository
{
    /**
     * 
     * @param string $token
     * @return Inextends\Tamandua\Models\User
     */
    public static function getCurrentUserFromToken($token)
    {
        $user = User::whereHas('authtoken', function ($query) use ($token) {
            $query->where('token', $token);
        })->first();
        
        return $user;
    }
    
    /**
     * 
     * @param int $id
     * @return Inextends\Tamandua\Models\User
     * @throws APIException
     */
    public function get($id) {
        try {
            return User::where('id', $id)
                         ->firstOrFail();
        } catch (\Exception $e) {
            throw new APIException(400401);
        }
    }
}
