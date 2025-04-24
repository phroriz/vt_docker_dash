<?php

namespace src\middlewares;

use core\Controller;
use src\handlers\UserHandler;

class AuthAdmMiddleware extends Controller
{
    public function handle($request, $response, $next)
    {
        $loggedUser = UserHandler::checkLogin();

        if (!$loggedUser ) {
            $this->redirect('/auth/signin');
            exit;
        }

        $request->user = $loggedUser;

        if(!UserHandler::checkUserAdm()){
            $this->redirect('/painel');
            exit;
        }
        
        return $next($request, $response);
        
        
    }
}
