<?php

include_once("cdatabase.php");
include_once("cmoduleauth.php");
include_once("cmoduleacademicyear.php");
include_once("ctemplatecontroller.php");

include_once("cmodulegroups.php");
include_once("cmodulediscipline.php");
include_once("cmoduleschedule.php");


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
            case 'discipline':
                $module = new CModuleDiscipline();
                break;
            case 'schedule':
                $module = new CModuleSchedule();
                break;
            default:
                $module = new CModuleGroups();
        }

        $module->content();
    }
}