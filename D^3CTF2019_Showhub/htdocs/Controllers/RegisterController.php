<?php

Class RegisterController extends BaseController
{
    public $username;
    public $password;
    public $confirm_password;

    public function index()
    {
        if ($this->request->post["username"] && $this->request->post["password"]) {
            $username = $this->request->post['username'];
            $password = $this->request->post['password'];
            if (User::register($username, $password)) {
                $this->redirect("/Manage");
            } else {
                return $this->render('index.html', ['msg' => 'Registration failed']);
            }
        }
        return $this->render('index.html', ['msg' => 'Registration failed, required fields missed']);

    }
}