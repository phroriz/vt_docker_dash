<?php

namespace src\handlers;
use src\models\Comment as ModelsComment;


class CommentHandler
{
    // Retorna um único resultado do Power BI pelo hash, validando se o usuário tem acesso ao grupo
    public static function new($input)
    {
        // Validação básica
        if (empty($input['comment']) || empty($input['dash'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Comentário ou grupo não informado']);
            return;
        }
    
        $comment = trim($input['comment']);
        $groupHash = $input['group'];
        $user = UserHandler::checkLogin();
        $dash = dashHandler::getByHash($input['dash']);

        if (!$dash) {
            http_response_code(404);
            echo json_encode(['error' => 'Grupo não encontrado']);
            return;
        }
    
        // Inserção (você pode ajustar o model conforme sua estrutura)
        $result = ModelsComment::insert([
            'dashs_id' => $dash->id,
            'user_id'   => $user->id, // ou outro método que pegue o usuário logado
            'text'     => $comment
        ])->execute();
    
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Comentário salvo com sucesso']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao salvar comentário']);
        }
    }

    public static function get($dash_id){
        $comments = ModelsComment::select()
                                    ->where('dashs_id', $dash_id)
                                    ->orderBy('created_at', 'desc')
                                    ->get();

        if($comments){
            return $comments;
            exit;
        } 

        return false;
    }
    
}
