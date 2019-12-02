<?php

class ManageController extends BaseController
{
    public function index()
    {
        return $this->render('welcome.html', ["username" => $this->request->user]);
    }
}