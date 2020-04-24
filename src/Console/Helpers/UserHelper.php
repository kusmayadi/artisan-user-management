<?php

namespace Kusmayadi\ArtisanUser\Console\Helpers;
use Kusmayadi\ArtisanUser\Models\User;

class UserHelper
{
    public static function getUser($idOrEmail)
    {
        if (is_numeric($idOrEmail)) {
            return $user = UserHelper::getUserById($idOrEmail);
        } else {
            return $user = UserHelper::getUserByEmail($idOrEmail);
        }
    }

    public static function noUserMessage($idOrEmail)
    {
        $label = is_numeric($idOrEmail) ? 'id' : 'email';

        return 'User with ' . $label . ' ' . $idOrEmail . " is not exists.\nRun 'php artisan user:list' to see list of users with their " . $label . 's.';
    }

    private static function getUserById($id)
    {
        return User::where('id', $id)->first();
    }

    private static function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }
}
