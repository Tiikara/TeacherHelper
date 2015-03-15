<?php

include_once("cdatabase.php");
include_once("cmoduleacademicyear.php");
include_once("ctemplatecontroller.php");

class CModulePhasedControl {
    public function content()
    {
        $database = CDatabase::getInstance();

        if(isset($_POST['discipline_group']))
        {
            $this->createReport($_POST['discipline_group'], $_POST['date-from'], $_POST['date-to']);
        }

        $disciplineGroups = $database->getDisciplineGroups(CModuleAcademicYear::getId());

        CTemplateController::drawPhasedControlSelect($disciplineGroups);
    }

    private function createReport($idDisciplineGroup, $dateFrom, $dateTo)
    {
        $database = CDatabase::getInstance();

        $academDate = CModuleAcademicYear::getAcademDate();

        if($academDate['semester'] == 1)
        {
            $startDateAcadem = date("Y")."-01-01";
        }
        else
        {
            $startDateAcadem = date("Y")."-09-01";
        }

        $discipline = $database->getDisciplineFromIdDisciplineGroup($idDisciplineGroup);
        $tasks = $database->getTasksMaxDate($discipline['id'], $dateTo);

        $allDifficulty = 0;
        foreach($tasks as $task)
        {
            $allDifficulty += $task['difficulty'];
        }

        $students = $database->getStudentsFromDisciplineGroup($idDisciplineGroup);
        for($i=0;$i<count($students);$i++)
        {
            $doneTasks = $database->getDoneTasks($discipline['id'], $students[$i]['id'], $dateTo);
            $studentDifficulty = 0;
            foreach($doneTasks as $task)
            {
                $studentDifficulty += $task['difficulty'];
            }

            $students[$i]['rating'] = ($studentDifficulty / $allDifficulty) * 3 + 2;
        }

        CTemplateController::drawPhasedControlReport($students);
    }
}