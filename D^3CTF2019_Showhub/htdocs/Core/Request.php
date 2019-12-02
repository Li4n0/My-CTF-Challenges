<?php

class Request
{
    public $method = null;
    public $get = null;
    public $post = null;
    public $cookie = null;
    public $headers = null;
    public $session = null;
    public $user = null;


    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->get = $_GET;
        $this->post = $_POST;
        $this->cookie = $_COOKIE;
        $this->headers = getallheaders();
        $this->session = $_SESSION;
        if (array_key_exists('uid', $this->session)) {
            $this->user = User::findOne(['id'=>$this->session['uid']]);
        }
    }
}