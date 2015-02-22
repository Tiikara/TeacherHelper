<?php

include_once("clogin.php");


class CModuleAuth {

    public function checkAuth()
    {
        if($_GET['module'] == "register")
        {
            // TODO: Модуль регистрации
        }

        $login  = new CLogin();
        $login->tryLogin();
    }
}