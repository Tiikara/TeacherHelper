<?php

include_once("cdatabase.php");
include_once("cmoduleauth.php");
include_once("ctemplatecontroller.php");


class CMainController {

    public function content()
    {
        $sqldb = CDatabase::createInstance();
        $sqldb->connect();

        $moduleAuth = new CModuleAuth();
        $moduleAuth->checkAuth();

        // TODO: Обработка модулей
        CTemplateController::drawJournal();
    }
}