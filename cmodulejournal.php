<?php

include_once("cdatabase.php");
include_once("cmoduleacademicyear.php");
include_once("ctemplatecontroller.php");

class CModuleJournal {

    public function content()
    {
        $database = CDatabase::getInstance();

        if(isset($_GET['date']))
        {
            $this->editInDate($_GET['date']);
        }

        $today_time  = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
        $today = date("Y-m-d", $today_time);

        CTemplateController::drawJournalSelectDate($today);
    }

    private function editInDate($date)
    {
        $database = CDatabase::getInstance();

        if(isset($_GET['skip']))
        {
            $database->addEventSkip($_GET['discipline_group'], $date, $_GET['skip']);
        }

        if(isset($_GET['delete']))
        {
            $database->deleteEvent($_GET['delete']);
            exit;
        }

        if(isset($_GET['add']))
        {
            $this->addEvent($date, $_GET['add'], $_GET['discipline_group']);
        }

        $dayOfWeek = date("N", strtotime($date)) - 1;
        $numWeek = date("W", strtotime($date));

        $schedule = $database->getScheduleOfDay(CModuleAcademicYear::getId(), $dayOfWeek);

        $schedule_out = array();

        $dateEvents = $database->getDateEvents(CModuleAcademicYear::getId(), $date);

        for($i=0;$i<count($schedule);$i++)
        {
            if($schedule[$i]['type_alternation'] == 1 &&
                $numWeek % 2 != 0)
            {
                continue;
            }

            if($schedule[$i]['type_alternation'] == 2 &&
                $numWeek % 2 == 0)
            {
                continue;
            }

            $students = $database->getStudents($schedule[$i]['id_group']);

            for($j=0;$j<count($students);$j++)
            {
                $students[$j]['event'] = null;

                foreach($dateEvents as $dateEvent)
                {
                    if($students[$j]['id'] == $dateEvent['id_student'])
                    {
                        $students[$j]['event'] = $dateEvent;

                        if($students[$j]['event']['id_tasks'] == "")
                        {
                            $students[$j]['event']['task'] = null;
                        }
                        else
                        {
                            $students[$j]['event']['task'] = $database->getTaskFromId($students[$j]['event']['id_tasks']);
                        }
                        break;
                    }
                }
            }

            $schedule[$i]['students'] = $students;
            $schedule_out[] = $schedule[$i];
        }

        CTemplateController::drawJournalDate($date, $schedule_out);
    }

    private function addEvent($date, $idStudent, $idDisciplineGroup)
    {
        $database = CDatabase::getInstance();

        if(isset($_POST['rating']))
        {
            $database->addEvent($idDisciplineGroup, $date, $idStudent, $_POST['rating'], $_POST['id_task']);
            return;
        }

        $discipline = $database->getDisciplineFromIdDisciplineGroup($idDisciplineGroup);

        $tasks = $database->getTasks($discipline['id']);

        CTemplateController::drawJournalAddEvent($date, $idStudent, $idDisciplineGroup, $tasks);
    }
}