<?php

namespace src\helpers;

use src\models\User;

class CommentHelper
{


    public static function renderComments($comments): string
    {
         // Se não tiver comentários, mostra mensagem amigável
         if (empty($comments)) {
            return '<p class="text-muted">Nenhum comentário disponível.</p>';
            exit;
        }
        
            $html = '';
           
            foreach ($comments as $comment) {
           
            $user = (object) User::select()->where('id', $comment['user_id'])->one();
            $name = htmlspecialchars($user->name ?? 'Anônimo');
            $dataTime =  DateHelper::formatShort($comment['created_at']) ?? '';
            $email = trim(strtolower($user->email));
            $gravatarUrl = 'https://www.gravatar.com/avatar/' . md5($email) . '?s=100&d=identicon';
            $avatar =  $gravatarUrl;
            $text = htmlspecialchars($comment['text'] ?? '');

            $html .= <<<HTML
<div class="card" style="background-color:rgb(250, 250, 250);">
    <div class="card-body ">
        <div class="d-flex mb-1">
            <div class="flex-shrink-0">
                <img src="{$avatar}" alt="user-image" class="user-avtar wid-35">
            </div>
            <div class="flex-grow-1 ms-3">
                <h5 class="card-title">{$name}</h5>
                <h6 class="card-subtitle mb-2 text-muted">{$dataTime}</h6>
            </div>
        </div>
        <p class="card-text">{$text}</p>
    </div>
</div>
HTML;
        }

        return $html;
    }
}
