<?php

namespace src\handlers;

use src\models\PowerBi;

class PowerBiHandler
{
    // Retorna um único resultado pelo hash
    public static function getByHash(string $hash)
    {
        return PowerBi::select()
            ->where('hash', $hash)
            ->where('groups_id', 1)
            ->one();
    }

    // Retorna todos para o menu
    public static function getAllForMenu(string $idGroup): array
    {
        return PowerBi::select()
            ->where('groups_id', $idGroup)
            ->get();
    }
}
