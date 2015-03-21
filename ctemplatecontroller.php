<?php


class CTemplateController {

    static function drawLoginScreen($message = null)
    {
        include "tpl/login.html";
        exit;
    }

    static function drawRegisterScreen($message = null)
    {
        include "tpl/register.html";
        exit;
    }

    static function drawAcademicYear()
    {
        include "tpl/academicyear.html";
        exit;
    }

    static function drawGroups($groups)
    {
        $bodyName = "tpl/groups.html";
        include "tpl/main.html";
        exit;
    }

    static function drawStudents($group, $students)
    {
        $bodyName = "tpl/editgroup.html";
        include "tpl/main.html";
        exit;
    }

    static function drawEditStudent($group, $student)
    {
        $bodyName = "tpl/editstudent.html";
        include "tpl/main.html";
        exit;
    }

    static function drawDiscipline($disciplines)
    {
        $bodyName = "tpl/discipline.html";
        include "tpl/main.html";
        exit;
    }

    static function drawEditDiscipline($discipline, $groups)
    {
        $bodyName = "tpl/editdiscipline.html";
        include "tpl/main.html";
        exit;
    }

    static function drawJournal()
    {
        $bodyName = "tpl/journal.html";
        include "tpl/main.html";
        exit;
    }

    static function drawSchedule($schedule)
    {
        $bodyName = "tpl/schedule.html";
        include "tpl/main.html";
        exit;
    }

    static function drawAddSchedule($dayOfWeek, $type_lectures, $disc_groups)
    {
        $bodyName = "tpl/addschedule.html";
        include "tpl/main.html";
        exit;
    }

    static function drawTasksSelect($disciplines)
    {
        $bodyName = "tpl/tasksselect.html";
        include "tpl/main.html";
        exit;
    }

    static function drawTasksEdit($idDiscipline, $tasks)
    {
        $bodyName = "tpl/tasks.html";
        include "tpl/main.html";
        exit;
    }

    static function drawJournalSelectDate($datetoday)
    {
        $bodyName = "tpl/journalselectdate.html";
        include "tpl/main.html";
        exit;
    }

    static function drawJournalDate($date, $schedule)
    {
        $bodyName = "tpl/journaldate.html";
        include "tpl/main.html";
        exit;
    }

    static function drawJournalAddEvent($date, $idStudent, $idDisciplineGroup, $tasks)
    {
        $bodyName = "tpl/addevent.html";
        include "tpl/main.html";
        exit;
    }

    static function drawPhasedControlSelect($disciplineGroups)
    {
        $bodyName = "tpl/phasedcontrolselect.html";
        include "tpl/main.html";
        exit;
    }

    static function drawPhasedControlReport($students)
    {
        $bodyName = "tpl/phasedcontrolreport.html";
        include "tpl/main.html";
        exit;
    }
}