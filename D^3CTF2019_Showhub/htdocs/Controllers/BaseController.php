<?php

class BaseController
{
    public $request = null;
    private $template = null;

    public function __construct(Request $request, \Twig\Environment $template)
    {
	$this->request = $request;
        $this->template = $template;
    }

    protected function render(...$args)
    {
        return $this->template->render(...$args);
    }

    protected function redirect($url)
    {
        header("Location: $url");
    }

    protected function json($arr)
    {
        header("Content-Type: application/json");
        return json_encode($arr);
    }

    protected function responseNotFound($username = null)
    {
        header("HTTP/1.0 404 Not Found");
        return $this->render('404.html',['username'=>$username]);
    }

    protected function responseNotAllowed($msg = null, $username = null)
    {
        header("HTTP/1.0 403 Not Allowed");
        return $this->render('403.html', ["msg" => $msg, "username" => $username]);
    }
}
