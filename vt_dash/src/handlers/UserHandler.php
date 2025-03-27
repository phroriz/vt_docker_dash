<?php

namespace src\handlers;

use src\models\User;

class UserHandler
{
    public static function checkLogin()
    {
        if (!isset($_SESSION['user'])) {
            return false;
        }

        $id = $_SESSION['user'];

        $user = User::select()->where('id', $id)->one();

        if ($user) {
            return (object)$user;
        }

        return false;
    }
}
