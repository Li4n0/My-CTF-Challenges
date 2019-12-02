<?php

class IndexController extends BaseController
{
    public function index()
    {
        return $this->render('index.html', ["username" => $this->request->user->username]);
    }
}