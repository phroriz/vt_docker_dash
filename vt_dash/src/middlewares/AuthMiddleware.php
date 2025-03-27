<?php

namespace src\middlewares;

use core\Controller;
use src\handlers\UserHandler;

class AuthMiddleware extends Controller
{
    public function handle($request, $response, $next)
    {
        $loggedUser = UserHandler::checkLogin();

        if (!$loggedUser) {
            $this->redirect('/auth/signin');
        }

        $request->user = $loggedUser;

        return $next($request, $response);
    }
}
