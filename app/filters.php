<?php
	// Auth Filter
	$router->filter('auth', function(){
	    \app\middlewares\AuthMiddleware::isLoggedIn();
	});

	// System Role Permission
	$router->filter('isAdmin', function(){
	    if (!\app\middlewares\RoleMiddleware::isAdmin()) {
	    	\app\helpers\UrlHelper::redirect('');
	    }
	});

	$router->filter('isSeller', function(){
	    if (!\app\middlewares\RoleMiddleware::isSeller()) {
	    	\app\helpers\UrlHelper::redirect('');
	    }
	});

	$router->filter('isAnalyst', function(){
		if (!\app\middlewares\RoleMiddleware::isAnalyst()) {
	    	\app\helpers\UrlHelper::redirect('');
	    }
	});