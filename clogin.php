<?php

include_once('libs/recaptchalib.php');
include_once("ctemplatecontroller.php");

include_once("cdatabase.php");

class CLogin {

    public function tryLogin()
    {
        ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] .'/admin/sessions');
        ini_set('session.gc_maxlifetime', 60*60);
        ini_set('session.cookie_lifetime', 0);

        session_start();

        if($_GET['action'] == "exit")
        {
            session_destroy();
            header("Location: index.php");
            exit;
        }

        if(!isset($_SESSION['is_login']))
        {
            $_SESSION['is_login'] = false;
        }

        if($_SESSION['is_login'] == false)
        {
            $idUser = $this->getIdUser();

            if($this->isValidCaptcha()  == false ||
                $idUser == -1)
            {
                session_destroy();
                $captcha = recaptcha_get_html($this->publickey, null);
                CTemplateController::drawLoginScreen($captcha);
            }

            $_SESSION['is_login'] = true;
            $_SESSION['id_user'] = $idUser;
        }
    }

    /**
     * Проверка каптчи
     *
     * @return boolean
     */
    private function isValidCaptcha()
    {
        if(isset($_POST['recaptcha_response_field']))
        {
            $resp = recaptcha_check_answer ($this->privatekey,
                $_SERVER["REMOTE_ADDR"],
                $_POST["recaptcha_challenge_field"],
                $_POST["recaptcha_response_field"]);

            return $resp->is_valid;
        }

        return false;
    }

    /**
     * Проверка логина и пароля
     *
     * @return int
     */
    private function getIdUser()
    {
        $database = CDatabase::getInstance();

        if(!isset($_POST['name']) ||
            !isset($_POST['password']))
        {
            return -1;
        }

        $md5hash = md5($_POST['name'].$_POST['password']);

        return $database->tryLogin($_POST['name'], $md5hash);
    }

    // Ключи для сервиса google reCaptcha. Получить ключи: https://www.google.com/recaptcha/admin
    private $publickey = "6LcH0vUSAAAAACWptTRDGY1y0fCevYfTiLzsVRx_";
    private $privatekey = "6LcH0vUSAAAAAC9Fbnm10dHPwOL2vY84Lqj8xpFL";
}