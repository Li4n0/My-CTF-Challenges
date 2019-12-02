<?php

Class User extends Model
{
    public $id = null;
    public $username = "";
    public $password = "";

    public function __construct($id = null, $username = null, $password = null)
    {
        parent::__construct();
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
    }

    public function __toString()
    {
        return $this->username;
    }

    public function register($username, $password)
    {
        $password = hash("sha256", $password);
        $user = (new User(null, $username, $password))->save();
        if ($user) {
            $_SESSION['uid'] = $user->id;
            return $user;
        }
        return false;
    }

    public function login($username, $password)
    {
        $password = hash("sha256", $password);
        $user = User::findOne(["username" => $username, "password" => $password]);
        if ($user) {
            $_SESSION['uid'] = $user->id;
            return $user;
        }
        return false;
    }

    public function logout()
    {
        session_destroy();
    }
}