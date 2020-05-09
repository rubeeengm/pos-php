<?php
namespace core;

class Controller {
    protected $provider;

    public function __construct() {
        $config = ServicesContainer::getConfig();
        
        $loader = new \Twig_Loader_Filesystem(_APP_PATH_ . 'views/');

        $this->provider = new \Twig_Environment($loader, array(
            'cache' => !$config['cache'] ? false : _CACHE_PATH_,
            'debug' => true,
        ));

        $this->provider->addExtension(new \Twig_Extension_Debug());

        // My custom filters
        $this->addCustomFilters();
    }

    private function addCustomFilters(){
        // Filter
        $this->provider->addFilter(new \Twig_SimpleFilter('public', ['app\\helpers\\UrlHelper', 'public']));
        $this->provider->addFilter(new \Twig_SimpleFilter('url', ['app\\helpers\\UrlHelper', 'base']));
        $this->provider->addFilter(new \Twig_SimpleFilter('padLeft', function($input, $zeros = 4){
            return str_pad($input, $zeros, '0', STR_PAD_LEFT);
        }));

        // Functions
        $this->provider->addFunction(new \Twig_SimpleFunction('user', ['core\\Auth', 'getCurrentUser']));
        $this->provider->addFunction(new \Twig_SimpleFunction('isAdmin', ['app\\middlewares\\RoleMiddleware', 'isAdmin']));
        $this->provider->addFunction(new \Twig_SimpleFunction('isSeller', ['app\\middlewares\\RoleMiddleware', 'isSeller']));
        $this->provider->addFunction(new \Twig_SimpleFunction('isAnalyst', ['app\\middlewares\\RoleMiddleware', 'isAnalyst']));
    }

    protected function render(string $view, array $data = []) : string {
        return $this->provider->render($view, $data);
    }
}