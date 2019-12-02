<?php

class WebConsoleController extends BaseController
{
    public function index()
    {
	$client_ip = $SERVER['HTTP_CLIENT_IP'];    
	if ($this->request->user->username === "admin" && !filter_var(
                $_SERVER['HTTP_CLIENT_IP'],
                FILTER_VALIDATE_IP,
                FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
            )) {

            return $this->render('webconsole.html', ["username" => $this->request->user]);
        } elseif ($this->request->user->username !== "admin") {
            return $this->responseNotAllowed("Only administrators in the internal network environment can access,you are not admin", ["username" => $this->request->user]);
        } else {
            return $this->responseNotAllowed("Only administrators in the internal network environment can access,your client ip($client_ip) is not be allowed, please visit in the internal network environment", ["username" => $this->request->user]);
        }

    }

    public function exec()
    {
        if ($this->request->user->username === "admin" && !filter_var(
                $_SERVER['HTTP_CLIENT_IP'],
                FILTER_VALIDATE_IP,
                FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
            )) {
            $cmd = $this->request->post['cmd'];
            return $this->json(array("result" => shell_exec($cmd)));
        } elseif ($this->request->user->username !== "admin") {
            return $this->responseNotAllowed("Only administrators in the internal network environment can access,you are not admin");
        } else {
            return $this->responseNotAllowed("Only administrators in the internal network environment can access,your client ip($client_ip) is not be allowed, please visit in the internal network environment", ["username" => $this->request->user]);
        }

    }
}
