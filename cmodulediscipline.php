<?php

include_once("cdatabase.php");
include_once("cmoduleacademicyear.php");
include_once("ctemplatecontroller.php");

class CModuleDiscipline {


    public function content()
    {
        $database = CDatabase::getInstance();

        if(isset($_GET['editname']))
        {
            $database->updateNameDiscipline($_GET['editname'], $_POST['ajax_post_value']);
            exit;
        }

        if(isset($_GET['edit']))
        {
           $this->editDisciplineContent($_GET['edit']);
        }

        if(isset($_GET['delete']))
        {
            $database->deleteDiscipline($_GET['delete']);
            exit;
        }

        if($_GET['action'] == 'add')
        {
            $add_status = $database->addDiscipline($_POST['name'], CModuleAcademicYear::getId());

            if($add_status == false)
            {
                exit;
            }

            $discipline = $database->getDisciplineFromName($_POST['name'], CModuleAcademicYear::getId());

            $disciplineJson['id'] = $discipline['id'];
            $disciplineJson['name'] = $discipline['name'];

            echo json_encode($disciplineJson);
            exit;
        }

        $disciplines = $database->getDisciplines(CModuleAcademicYear::getId());

        $addTemplate['isAddTemplate'] = true;
        $addTemplate['id'] = '::id::';
        $addTemplate['name'] = '::name::';

        $disciplines[] = $addTemplate;

        CTemplateController::drawDiscipline($disciplines);
    }

    private function editDisciplineContent($idDiscipline)
    {
        $database = CDatabase::getInstance();

        if(isset($_GET['connect']))
        {
            $database->connectDisciplineToGroup($idDiscipline, $_GET['connect']);
        }

        if(isset($_GET['disconnect']))
        {
            $database->disconnectDisciplineFromGroup($idDiscipline, $_GET['disconnect']);
        }

        $groupsRelated = $database->getGroupsRelatedDiscipline($idDiscipline);
        $groupsTemp = $database->getGroups(CModuleAcademicYear::getId());
        $discipline = $database->getDiscipline($idDiscipline);


        $groups = array();
        foreach($groupsTemp as $group)
        {
            $isFounded = false;
            foreach($groupsRelated as $groupRelated)
            {
                if($groupRelated['id'] == $group['id'])
                {
                    $isFounded = true;
                    break;
                }
            }

            $group['isRelated'] = $isFounded;
            $groups[] = $group;
        }

        CTemplateController::drawEditDiscipline($discipline, $groups);
    }
}