<?php

namespace src\handlers;

use src\models\Dash;
use src\models\Rating;
use src\models\viewNpsDashboard;
use src\models\ViewUsersGroup;

class NpsHandler
{

    public static function new(array $input)
    {
        if (empty($input['nota']) || empty($input['hash'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados incompletos.']);
            exit;
        }

        $dash = DashHandler::getByHash($input['hash']);

        if (!$dash) {
            http_response_code(404);
            echo json_encode(['error' => 'Dashboard não encontrado.']);
            exit;
        }

        $userId = $_SESSION['user'] ?? null;

        if (!$userId) {
            http_response_code(401);
            echo json_encode(['error' => 'Usuário não autenticado.']);
            exit;
        }



        if (self::last30day($dash->id)) {
            http_response_code(401);
            echo json_encode(['error' => 'NPS ja coletado']);
            exit;
        }

        $score = (int) $input['nota'];
        $suggestion = trim($input['sugestao'] ?? '');

        Rating::insert([
            'user_id'      => $userId,
            'dashs_id'     => $dash->id,
            'score'        => $score,
            'suggestion'   => $suggestion,
            'submitted_at' => date('Y-m-d H:i:s')
        ])->execute();

        echo json_encode(['success' => true]);
    }

    public static function score($dash_id): int
    {
        $nps  = viewNpsDashboard::select()
            ->where('dashs_id', $dash_id)
            ->one();
        if (!empty($nps)) {
            return $nps['score'];
        }
        return 0;
    }

    public static function checkQuest($dash_id, $qtd_access)
    {

        $rating = self::last30day($dash_id);
        if (empty($rating) && $qtd_access > 5) return true;
        return false;
    }

    private static function last30day($dash_id)
    {
        $user = UserHandler::checkLogin();
        $limitDate = date('Y-m-d H:i:s', strtotime('-30 days'));

        return Rating::select()
            ->where('user_id', $user->id)
            ->where('dashs_id', $dash_id)
            ->where('submitted_at', '>=', $limitDate)
            ->execute();
    }
}
