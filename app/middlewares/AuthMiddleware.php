<?php
namespace app\middlewares;

use app\helpers\UrlHelper,
    core\Auth;

class AuthMiddleware {
    public static function isLoggedIn() {
        if(!Auth::isLoggedIn()) {
            UrlHelper::redirect('auth');
        }
    }
}