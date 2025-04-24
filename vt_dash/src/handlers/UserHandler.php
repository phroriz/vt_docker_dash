<?php

namespace src\handlers;

use src\models\Admin;
use src\models\Group;
use src\models\User;
use src\models\UsersGroup;
use src\models\ViewUserMenu;
use src\models\ViewUsersGroup;

class UserHandler
{
    /**
     * Autentica o usuário com base em email e senha.
     */
    public static function auth(): void
    {
        header('Content-Type: application/json');
    
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
    
        if (empty($email) || empty($password)) {
            self::jsonResponse('error', 'Erro', 'Preencha todos os campos.');
            return;
        }
    
        $userData = User::select()
            ->where('email', $email)
            ->one();
    
        if (!$userData) {
            self::jsonResponse('error', 'Login inválido', 'Email ou senha incorretos.');
            return;
        }
    
        $user = (object) $userData;
    
        if ((int)$user->status === 0  && password_verify($password, $user->pass)) {
            self::jsonResponse('error', 'Acesso negado', 'Sem permissão para acessar o sistema.', '/auth/signin');
            return;
        }
    
        if ((int)$user->status === 1 && password_verify($password, $user->pass)) {
            $_SESSION['user_id'] = $user->id;
            self::jsonResponse('success', 'Primeiro acesso', 'Defina sua senha.', '/auth/signin/login/first');
            return;
        }
    
        if (password_verify($password, $user->pass)) {
            User::update()
                ->where('id', $user->id)
                ->set('last_login', date('Y-m-d H:i:s'))
                ->execute();
    
            $_SESSION['user'] = $user->id;
    
            self::jsonResponse('success', 'Login realizado', 'Redirecionando...', '/painel');
            return;
        }
    
        self::jsonResponse('error', 'Login inválido', 'Email ou senha incorretos.');
    }
    


    public static function resetPass(array $input): bool
    {
        if (empty($input['password']) || strlen($input['password']) < 6) {
            return false; // Senha inválida
        }

        if (!isset($_SESSION['user_id'])) {
            return false; // Sessão inválida
        }

        $user_id = $_SESSION['user_id'];
        $passwordHash = password_hash($input['password'], PASSWORD_DEFAULT); // mais seguro que md5

        $result = User::update()
            ->where('id', $user_id)
            ->set('pass', $passwordHash)
            ->set('fisth_login', date('Y-m-d H:i:s'))
            ->set('status', 2)
            ->execute();

        if ($result) {
            // Reseta sessão temporária
            $_SESSION['user'] = $user_id;
            unset($_SESSION['user_id']);
            return true;
        }

        return false;
    }

    /**
     * Realiza o logoff do usuário.
     */
    public static function logoff()
    {
        session_destroy();
    }

    /**
     * Verifica se o usuário está logado e retorna seus dados com grupos.
     */
    public static function checkLogin()
    {
        if (!isset($_SESSION['user'])) {
            return false;
        }

        $userId = $_SESSION['user'];
        $user = User::select()->where('id', $userId)->one();

        if (!$user) {
            return false;
        }

        $user = (object) $user;
        $userGroups = UsersGroup::select()->where('users_id', $user->id)->get();
        $user->group = (object) $userGroups;

        return $user;
    }

    /**
     * Retorna os dados do usuário logado.
     */
    public static function user()
    {
        $userId = $_SESSION['user'] ?? null;

        if (!$userId) {
            return false;
        }

        return (object) User::select()->where('id', $userId)->one();
    }

    /**
     * Retorna um usuário pelo email.
     */
    public static function getUser($email)
    {
        $user = User::select()->where('email', $email)->one();
        return $user ? (object) $user : false;
    }

    /**
     * Verifica se o usuário atual é um admin.
     */
    public static function checkUserAdm()
    {
        $user = self::checkLogin();

        if (!$user) {
            return false;
        }

        return Admin::select()->where('user_id', $user->id)->execute();
    }

    /**
     * Retorna o menu do usuário de acordo com o grupo.
     */
    public static function getAllForMenu()
    {
        $user = self::checkLogin();
        return ViewUserMenu::select()
            ->where('user_id', $user->id)
            ->where(function ($q) {
                $q->where('group_expires', '>=',  date('Y-m-d'));
                $q->orWhere('group_expires', '=',  '0000/00/00');
            })
            ->where('group_status', 1)
            ->get();
    }

    /**
     * Cria um novo usuário.
     */
    public static function newUser($email, $name)
    {
        $senhaHash = password_hash('123456', PASSWORD_DEFAULT);
        return User::insert([
            'email' => $email,
            'name' => $name,
            'pass' =>  $senhaHash,
            'status' => 1
        ])->execute();
    }

    /**
     * Helper para resposta JSON padrão.
     */
    private static function jsonResponse($status, $title, $message, $redirect = null)
    {
        $response = [
            'status' => $status,
            'title' => $title,
            'message' => $message
        ];

        if ($redirect) {
            $response['redirect'] = $redirect;
        }

        echo json_encode($response);
    }
}
