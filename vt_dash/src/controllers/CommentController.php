<?php

namespace src\controllers;

use core\Controller;
use Google\Service\YouTube\CommentThread;
use src\handlers\CommentHandler;
use src\handlers\GroupHandler;
use src\handlers\dashHandler;
use src\handlers\UserHandler;
use src\models\dash;

class CommentController extends Controller
{

    public function new()
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $comment = CommentHandler::new($input);
        return $comment;
    }


}