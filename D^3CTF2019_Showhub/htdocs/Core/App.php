<?php

class App
{
    public $controller;
    public $action;
    public $template;

    public function __construct()
    {
        $path_info = substr($_SERVER['PATH_INFO'], 1);
        $info = explode('/', $path_info, 2);
        if ($info[0] == null) {
            $info[0] = 'Index';
            $info[1] = 'index';
        } else if (count($info) == 1 || $info[1] == null) {
            $info[1] = 'index';
        }
        if ($info[0] !== "templates") {
            $this->controller = $info[0] . 'Controller';
            $this->action = $info[1];
        }
        $loader = new \Twig\Loader\FilesystemLoader(WEB_PATH . '/Templates/');
        $this->template = new \Twig\Environment($loader, [
            'cache' => WEB_PATH . '/Cache',
        ]);

    }

    public function run($request)
    {
        if (class_exists($this->controller)) {
            $object = new $this->controller($request, $this->template);
            if (method_exists($object, $this->action)) {
                return call_user_func_array(array($object, $this->action), []);
            }
        }
        return $this->template->render('404.html');


    }

}
