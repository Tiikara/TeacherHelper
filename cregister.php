<?php

include_once("ctemplatecontroller.php");
include_once("ccaptcha.php");
include_once("cdatabase.php");

class CRegister {

    public function content()
    {
        if(isset($_POST['name']))
            $this->startRegister();

        CTemplateController::drawRegisterScreen();
    }

    private function startRegister()
    {
        $database = CDatabase::getInstance();
        $md5hash = md5($_POST['name'].$_POST['password']);

        $isRegistered = $database->registerUser($_POST['name'], $md5hash);

        if(CCaptcha::isValid() == false)
            CTemplateController::drawRegisterScreen("Неверно введена каптча!");

        if($isRegistered == false)
            CTemplateController::drawRegisterScreen("Пользователь с таким логином уже существует!");

        CTemplateController::drawRegisterScreen("Вы успешно зарегистрированы!");
    }
}