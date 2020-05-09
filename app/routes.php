<?php
/* Controllers */
$router->group(['before' => 'auth'], function($router){
	$router->controller('/home', 'app\\controllers\\HomeController');

	$router->group(['before' => 'isSeller'], function($router){
		$router->controller('/cliente', 'app\\controllers\\ClienteController');
		$router->controller('/comprobante', 'app\\controllers\\ComprobanteController');
		$router->controller('/producto', 'app\\controllers\\ProductoController');
	});
	
	$router->group(['before' => 'isAnalyst'], function($router){
		$router->controller('/reporte', 'app\\controllers\\ReporteController');	
	});
	

	$router->group(['before' => 'isAdmin'], function($router){
		$router->controller('/usuario', 'app\\controllers\\UsuarioController');	
	});
	
});

$router->controller('/auth', 'app\\controllers\\AuthController');

$router->get('/', function(){
    if(!\core\Auth::isLoggedIn()){
        \app\helpers\UrlHelper::redirect('auth');
    } else {
        \app\helpers\UrlHelper::redirect('home');
    }
});

$router->get('/welcome', function(){
    return 'Welcome page';
}, ['before' => 'auth']);

$router->get('/test', function(){
    return 'Welcome page';
}, ['before' => 'auth']);