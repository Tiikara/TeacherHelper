<?php

include_once("cdatabase.php");
include_once("cmoduleauth.php");
include_once("cmoduleacademicyear.php");
include_once("ctemplatecontroller.php");

include_once("cmodulegroups.php");


class CMainController {

    public function content()
    {
        $sqldb = CDatabase::createInstance();
        $sqldb->connect();

        $moduleAuth = new CModuleAuth();
        $moduleAuth->checkAuth();

        $moduleAcademicYear = new CModuleAcademicYear();
        $moduleAcademicYear->init();

        // TODO: Обработка модулей

        switch($_GET['module'])
        {
            case 'groups':
                $module = new CModuleGroups();
                break;
            default:
                $module = new CModuleGroups();
        }

        $module->content();
    }
}