<?php

namespace src\controllers;

use core\Controller;
use src\handlers\GroupHandler;
use src\handlers\DashHandler;
use src\handlers\UserHandler;
use src\models\dash;

class GruopController extends Controller
{
    /*private $loggedUser;
    public function __construct()
    {
        // Inicializa o usuário logado no construtor
        $this->loggedUser = UserHandler::checkLogin();
    }
    /**
     * Endpoint para receber o webhook e salvar a ação na fila.
     */
    public function list()
    {
        $groups = GroupHandler::getAllForMenu();
        $this->renderLayout('painel', 'adm/groups/list', [
            'groups' => $groups
        ]);
    }

    public function getGroupId($arg)
    {
        header('Content-Type: application/json');

        $id = $arg['id'];
        $groups = GroupHandler::getGroupById($id);

        // Se retornar um array com um único elemento, pegamos só o primeiro:
        if (is_array($groups) && count($groups) > 0) {
            echo json_encode($groups[0], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['error' => 'Grupo não encontrado']);
        }
    }

    public function getDashboardHash($arg)
    {
        header('Content-Type: application/json');

        $hash = $arg['hash'];
        $groups = GroupHandler::getDashboardById($hash);

        // Se retornar um array com um único elemento, pegamos só o primeiro:
        if (is_array($groups) && count($groups) > 0) {
            echo json_encode($groups[0], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['error' => 'Dash nao encontrado']);
        }
    }
    public function newDashboard()
    {
        header('Content-Type: application/json');

        // Captura e decodifica JSON bruto da requisição
        $input = json_decode(file_get_contents('php://input'), true);

        // Validação básica (ajuste conforme necessário)
        if (
            !isset($input['title'], $input['src'], $input['group']) ||
            empty($input['title']) || empty($input['src']) || empty($input['group'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => 'Campos obrigatórios ausentes']);
            return;
        }
        $group = GroupHandler::getGroupByHash($input['group']);
        $result = GroupHandler::newDashboard([
            'title'       => $input['title'],
            'description' => $input['description'] ?? '',
            'src'         => $input['src'],
            'group'       => $group->id,
            'status'      => (int) $input['status'] ?? 0
        ]);

        // Retorna resposta
        echo json_encode([
            'success' => true
        ]);
    }

    public function updateDashboard($arg)
    {
        header('Content-Type: application/json');

        // Pegamos o hash da URL
        $hash = $arg['hash'] ?? null;

        // Lê os dados do corpo (JSON)
        $input = json_decode(file_get_contents('php://input'), true);

        // Validação básica
        if (!$hash || !isset($input['title'], $input['description'], $input['src'], $input['status'])) {
            echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
            return;
        }

        // Organiza os dados a serem enviados ao handler
        $data = [
            'hash' => $hash,
            'title' => trim($input['title']),
            'description' => trim($input['description']),
            'src' => trim($input['src']),
            'status' => (int)$input['status']
        ];

        try {
            $success = GroupHandler::updateDashboardByHash($data);

            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Dashboard atualizado com sucesso' : 'Erro ao atualizar dashboard'
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erro inesperado ao atualizar dashboard',
                'error' => $e->getMessage()
            ]);
        }
    }



    public function updateGroup()
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $update = GroupHandler::groupUpdate($input);
        return $update;
    }

    public function createGroup()
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $update = GroupHandler::create($input);
        return $update;
    }


    public function viewGroup($arg)
    {
        $hashGroup = $arg['hash'];
        $group = GroupHandler::getGroupByHash($hashGroup);

        $group->expires = (new \DateTime($group->expires))->format('d/m/Y');
        $dashboards = GroupHandler::getDashboardAll($group->id);
        $users = GroupHandler::listUserGroup($group->id);
        $totalDashboards = count($dashboards);
        $totalAccess = array_sum(array_map(function ($dash) {  // Soma total de acessos
            return (int) $dash['qtd_access'];
        }, $dashboards));

        $totalUsers = count($users);
        $this->renderLayout('painel', 'adm/groups/view', [
            'dashboards' => $dashboards,
            'users' => $users,
            'groupHash' => $hashGroup,
            'group'     => $group,
            'totalDashboards' => $totalDashboards,
            'totalUsers' => $totalUsers,
            'totalAccess' => $totalAccess
        ]);
    }
    public function newUser()
    {


        // Lê os dados do corpo (JSON)
        $input = json_decode(file_get_contents('php://input'), true);

        // Validação básica
        if (!isset($input['email'], $input['group'], $input['name'])) {
            echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
            return;
        }

        $hashGroup = $input['group'];
        $group = GroupHandler::getGroupByHash($hashGroup);
        $user = UserHandler::getUser($input['email']);

        if ($user) {
            GroupHandler::newUserGroup($user->id, $group->id);
        } else {
            $userInsert[] = UserHandler::newUser($input['email'], $input['name']);
            GroupHandler::newUserGroup($userInsert[0], $group->id);
        }
        return true;
    }

    public function removeUserGroup()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $remove = GroupHandler::removeUserGroup($input);
        return $remove;
    }
}
