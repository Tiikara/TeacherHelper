<?php

include_once("cdatabase.php");
include_once("cmoduleacademicyear.php");
include_once("ctemplatecontroller.php");

class CModuleSchedule {

    public function content()
    {
        $database = CDatabase::getInstance();

        if(isset($_GET['delete']))
        {
            $database->deleteSchedule($_GET['delete']);
        }

        if(isset($_GET['add']))
        {
            $this->addSchedule($_GET['add']);
        }

        $schedule = array();

        for($i=0;$i<7;$i++)
        {
            $dayOfWeek = array();
            $dayOfWeek['day_week'] = $database->getScheduleOfDay(CModuleAcademicYear::getId(), $i);
            $schedule[] = $dayOfWeek;
        }

        $schedule[0]['name_day'] = 'Понедельник';
        $schedule[1]['name_day'] = 'Вторник';
        $schedule[2]['name_day'] = 'Среда';
        $schedule[3]['name_day'] = 'Четверг';
        $schedule[4]['name_day'] = 'Пятница';
        $schedule[5]['name_day'] = 'Суббота';
        $schedule[6]['name_day'] = 'Воскресенье';

        CTemplateController::drawSchedule($schedule);
    }

    private function addSchedule($dayOfWeek)
    {
        $database = CDatabase::getInstance();

        if(isset($_POST['num_lecture']))
        {
            $database->addSchedule(CModuleAcademicYear::getId(), $dayOfWeek, $_POST['num_lecture'], $_POST['type_alternation'], $_POST['disc_group'], $_POST['type_lecture']);
            return;
        }

        $disc_groups = $database->getDisciplineGroups(CModuleAcademicYear::getId());
        $type_lectures = $database->getTypeLectures();

        CTemplateController::drawAddSchedule($dayOfWeek, $type_lectures, $disc_groups);
    }

}