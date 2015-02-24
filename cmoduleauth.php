<?php

include_once("clogin.php");
include_once("cregister.php");


class CModuleAuth {

    public function checkAuth()
    {
        if($_GET['module'] == "register")
        {
            $register = new CRegister();
            $register->content();
        }

        $login  = new CLogin();
        $login->tryLogin();
    }
}