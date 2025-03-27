<?php

namespace src\handlers;

use src\models\Group;
use src\models\PowerBi;

class GroupHandler
{
    public static function getAllForMenu()
    {
        return Group::select()
            ->where('id', 1)
            ->get();
    }
}
