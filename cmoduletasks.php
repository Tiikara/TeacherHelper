<?php

include_once("cdatabase.php");
include_once("cmoduleacademicyear.php");
include_once("ctemplatecontroller.php");

class CModuleTasks {
    public function content()
    {
        $database = CDatabase::getInstance();

        if(isset($_GET['disc']))
        {
            $this->addTasks($_GET['disc']);
        }

        $disciplines = $database->getDisciplines(CModuleAcademicYear::getId());

        CTemplateController::drawTasksSelect($disciplines);
    }

    private function addTasks($idDiscipline)
    {
        $database = CDatabase::getInstance();

        if(isset($_POST['description']))
        {
            $database->addTask($idDiscipline, $_POST['description'], $_POST['date_to'], $_POST['difficulty']);
        }

        if(isset($_GET['delete']))
        {
            $database->deleteTask($_GET['delete']);
        }

        $tasks = $database->getTasks($idDiscipline);

        CTemplateController::drawTasksEdit($idDiscipline, $tasks);
    }
}