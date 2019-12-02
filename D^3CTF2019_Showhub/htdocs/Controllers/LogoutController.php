<?php
Class LogoutController extends BaseController
{

    public function index()
    {
        User::logout();
        $this->redirect('/');
    }
}