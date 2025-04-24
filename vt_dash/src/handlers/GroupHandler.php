<?php

namespace src\handlers;

use src\models\Group;
use src\models\dash;
use src\models\UsersGroup;
use src\models\ViewUserMenu;
use src\models\ViewUsersGroup;

use function Symfony\Component\Clock\now;

class GroupHandler
{
    public static function getAllForMenu()
    {
        return Group::select()
            ->get();
    }

    public static function getGroupById($id)
    {
        return Group::select()
            ->where('id', $id)
            ->get();
    }

    public static function getGroupByHash($hash)
    {
        return (object) Group::select()
            ->where('hash', $hash)
            ->one();
    }


    public static function create($input)
    {
        //  Validação básica
        $raw = bin2hex(random_bytes(16)) . uniqid('', true) . time();
        $hash = sha1($raw);
        if (
            !isset($input['name']) ||
            !isset($input['description']) ||
            !isset($input['expires']) ||
            !isset($input['status'])
        ) {
            echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
            return;
        }

        $name = trim($input['name']);
        $description = trim($input['description']);
        $expires = $input['expires'];
        $status = (int)$input['status'];

        Group::insert([
            'name' => $name,
            'description' => $description,
            'expires'   => $expires,
            'hash'      => $hash,
            'status' => $status
        ])->execute();
        echo json_encode(['success' => true]);
        return true;
    }
    public static function groupUpdate($input)
    {
        // ✅ Validação básica
        if (
            !isset($input['id']) ||
            !isset($input['name']) ||
            !isset($input['description']) ||
            !isset($input['expires']) ||
            !isset($input['status'])
        ) {
            echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
            return;
        }

        $id = (int)$input['id'];
        $name = trim($input['name']);
        $description = trim($input['description']);
        $expires = $input['expires'];
        $status = (int)$input['status'];

        Group::update()
            ->where('id', $id)
            ->set('name', $name)
            ->set('description', $description)
            ->set('expires', $expires)
            ->set('status', $status)
            ->execute();
        echo json_encode(['success' => true]);
        return true;
    }

    public static function getDashboardAll($idGroup)
    {
        return dash::select()
            ->where('groups_id', $idGroup)
            ->get();
    }
    public static function newDashboard($data)
    {
        $raw = bin2hex(random_bytes(16)) . uniqid('', true) . time();
        $hash = sha1($raw);

        return dash::insert([
            'hash' => $hash,
            'groups_id' => $data['group'],
            'title' => $data['title'],
            'description' => $data['description'],
            'src'  => $data['src']
        ])->execute();
    }
    public static function getDashboardById($hash)
    {
        return dash::select()
            ->where('hash', $hash)
            ->get();
    }
    public static function updateDashboardByHash($data)
    {
        return dash::update()
            ->where('hash', $data['hash'])
            ->set('title', $data['title'])
            ->set('description', $data['description'])
            ->set('src', $data['src'])
            ->set('status', $data['status'])
            ->execute();
    }


    public static function listUserGroup($groups_id)
    {
        return ViewUsersGroup::select()
            ->where('groups_id', $groups_id)
            ->get();
    }

    public static function newUserGroup($user_id, $group_id)
    {


        $userGroup = UsersGroup::select()
            ->where('users_id', $user_id)
            ->where('groups_id', $group_id)
            ->one();

        if (!$userGroup) {
            
            return UsersGroup::insert([
                'users_id' => $user_id,
                'groups_id' => $group_id
            ])->execute();
            
        } else {
            return false;
        }
    }

    public static function removeUserGroup($input)
    {

        if (
            !isset($input['user_id']) ||
            !isset($input['group_hash'])
        ) {
            echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
            return;
        }

        $group = self::getGroupByHash($input['group_hash']);
        $user = (object) ['id' => $input['user_id']];

        $userGroup = UsersGroup::delete()
            ->where('users_id', $user->id)
            ->where('groups_id', $group->id)
            ->execute();

        return true;
    }

    public static function check(object $group)
    {
        $user = UserHandler::checkLogin();
        return ViewUserMenu::select()
            ->where('user_id', $user->id)
            ->where(function ($q) {
                $q->where('group_expires', '>=',  date('Y-m-d'));
                $q->orWhere('group_expires', '=',  '00/00/0000');
            })
            ->where('group_hash', $group->hash)
            ->where('group_status', 1)
            ->get();
    }
}
