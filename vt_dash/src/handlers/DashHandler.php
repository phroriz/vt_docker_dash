<?php

namespace src\handlers;

use src\models\dash;
use src\models\ViewUsersGroup;

class dashHandler
{
// Retorna um único resultado do Power BI pelo hash, validando se o usuário tem acesso ao grupo
public static function getByHash(string $hash)
{
    // Puxa os dados do usuario logado
    $user = UserHandler::checkLogin();

    // Busca o dashboard do Power BI pelo hash
    $dash = (object) dash::select()
        ->where('hash', $hash)
        ->one();

    if (!$dash || !isset($dash->groups_id)) {
        return false;
    }

    // Valida se o usuário pertence ao grupo, o grupo está ativo e a data de expiração é válida
    $groups = ViewUsersGroup::select()
        ->where('user_id', $user->id)
        ->where('groups_id', $dash->groups_id)
        ->where('group_status', 1)
        ->where(function ($q) {
            $q->where('group_expires', '>=', date('Y-m-d'))
              ->orWhere('group_expires', '0000-00-00'); // Data sem expiração
        })
        ->get();

    // Se não encontrar grupo válido, bloqueia acesso
    if (empty($groups)) {
        return false;
    }

    Dash::update()
            ->where('hash', $hash)
            ->set('qtd_access', $dash->qtd_access + 1)
            ->set('last_access', date('Y-m-d H:i:s'))
            ->execute();

    // Retorna o objeto do dashboard se o acesso for permitido
    return $dash;
}


    // Retorna todos para o menu
    public static function getAllForMenu(string $idGroup): array
    {
        return dash::select()
            ->where('groups_id', $idGroup)
            ->where('status', 1)
            ->orderBy('title', 'asc')
            ->get();
    }

}
