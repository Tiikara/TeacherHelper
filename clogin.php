<?php

include_once("ctemplatecontroller.php");
include_once("cdatabase.php");

class CLogin {

    static public function getIdTeacher()
    {
        return self::$idTeacher;
    }

    public function tryLogin()
    {
        //ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] .'/admin/sessions');
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

            if($idUser == CDatabase::undefined_result)
            {
                session_destroy();
                CTemplateController::drawLoginScreen();
            }

            $_SESSION['is_login'] = true;
            $_SESSION['id_user'] = $idUser;
        }

        self::$idTeacher = $_SESSION['id_user'];
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
            return CDatabase::undefined_result;
        }

        $md5hash = md5($_POST['name'].$_POST['password']);

        return $database->getIdUser($_POST['name'], $md5hash);
    }

    static private $idTeacher;
}