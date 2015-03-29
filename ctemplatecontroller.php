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
        ob_start();
        include "tpl/groups.html";
        $body = ob_get_clean();

        self::drawBody($body);
    }

    static function drawStudents($group, $students)
    {
        ob_start();
        include "tpl/editgroup.html";
        $body = ob_get_clean();

        self::drawBody($body);
    }

    static function drawEditStudent($group, $student)
    {
        ob_start();
        include "tpl/editstudent.html";
        $body = ob_get_clean();

        self::drawBody($body);
    }

    static function drawDiscipline($disciplines)
    {
        ob_start();
        include "tpl/discipline.html";
        $body = ob_get_clean();

        self::drawBody($body);
    }

    static function drawEditDiscipline($discipline, $groups)
    {
        ob_start();
        include "tpl/editdiscipline.html";
        $body = ob_get_clean();

        self::drawBody($body);
    }

    static function drawJournal()
    {
        ob_start();
        include "tpl/journal.html";
        $body = ob_get_clean();

        self::drawBody($body);
    }

    static function drawSchedule($schedule)
    {
        ob_start();
        include "tpl/schedule.html";
        $body = ob_get_clean();

        self::drawBody($body);
    }

    static function drawAddSchedule($dayOfWeek, $type_lectures, $disc_groups)
    {
        ob_start();
        include "tpl/addschedule.html";
        $body = ob_get_clean();

        self::drawBody($body);
    }

    static function drawTasksSelect($disciplines)
    {
        ob_start();
        include "tpl/tasksselect.html";
        $body = ob_get_clean();

        self::drawBody($body);
    }

    static function drawTasksEdit($idDiscipline, $tasks)
    {
        ob_start();
        include "tpl/tasks.html";
        $body = ob_get_clean();

        self::drawBody($body);
    }

    static function drawJournalSelectDate($datetoday)
    {
        ob_start();
        include "tpl/journalselectdate.html";
        $body = ob_get_clean();

        self::drawBody($body);
    }

    static function drawJournalDate($date, $schedule)
    {
        ob_start();
        include "tpl/journaldate.html";
        $body = ob_get_clean();

        self::drawBody($body);
    }

    static function drawJournalAddEvent($date, $idStudent, $idDisciplineGroup, $tasks)
    {
        ob_start();
        include "tpl/addevent.html";
        $body = ob_get_clean();

        self::drawBody($body);
    }

    static function drawPhasedControlSelect($disciplineGroups)
    {
        ob_start();
        include "tpl/phasedcontrolselect.html";
        $body = ob_get_clean();

        self::drawBody($body);
    }

    static function drawPhasedControlReport($students)
    {
        ob_start();
        include "tpl/phasedcontrolreport.html";
        $body = ob_get_clean();

        self::drawBody($body);

    }

    static function drawTicketsSelectDiscipline($disciplines)
    {
        ob_start();
        include "tpl/ticketsselectdiscipline.html";
        $body = ob_get_clean();

        self::drawBody($body);
    }

    static function drawTicketsQuestions($idDiscipline, $questions, $themes)
    {
        ob_start();
        include "tpl/ticketsquestions.html";
        $body = ob_get_clean();

        self::drawBody($body);
    }

    static function drawTicketsEditTheme($idDiscipline, $themes)
    {
        ob_start();
        include "tpl/ticketsedittheme.html";
        $body = ob_get_clean();

        self::drawBody($body);
    }

    static function drawTicketsReport($tickets)
    {
        ob_start();
        include "tpl/ticketsreport.html";
        $body = ob_get_clean();

        self::drawBody($body);
    }

    private static function drawBody($body)
    {
        if(isset($_GET['ajax'])) {
            echo $body;
        } else {
            include "tpl/main.html";
        }

        exit;
    }
}