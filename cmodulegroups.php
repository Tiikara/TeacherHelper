<?php

include_once("cdatabase.php");
include_once("cmoduleacademicyear.php");
include_once("ctemplatecontroller.php");


class CModuleGroups {


    public function content()
    {
        $database = CDatabase::getInstance();

        if(isset($_GET['editname']))
        {
            $database->updateGroupName($_GET['editname'], $_POST['ajax_post_value']);
            exit;
        }

        if(isset($_GET['edit']))
        {
            $this->editGroupContent($_GET['edit']);
        }

        if(isset($_GET['delete']))
        {
            $database->deleteGroup($_GET['delete']);
            exit;
        }

        if($_GET['action'] == 'add')
        {
            $database->addGroup($_POST['name'], CModuleAcademicYear::getId());
        }

        $groups = $database->getGroups(CModuleAcademicYear::getId());
        CTemplateController::drawGroups($groups);
    }

    private function editGroupContent($idGroup)
    {
        $database = CDatabase::getInstance();

        if(isset($_GET['studentedit']))
        {
            if(isset($_POST['name']))
            {
                $database->updateStudent($_POST['name'] ,$_GET['studentedit']);
            }
            else
            {
                $group = $database->getGroup($idGroup);
                $student = $database->getStudent($_GET['studentedit']);
                CTemplateController::drawEditStudent($group, $student);
            }
        }

        if(isset($_GET['studentdelete']))
        {
            $database->deleteStudent($_GET['studentdelete']);
        }

        if($_GET['action'] == 'add')
        {
            $database->addStudent($_POST['name'], $idGroup);
        }

        $group = $database->getGroup($idGroup);
        $students = $database->getStudents($idGroup);

        CTemplateController::drawStudents($group, $students);
    }

}