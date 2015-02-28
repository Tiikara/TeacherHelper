<?php

include_once("ccaptcha.php");

class CTemplateController {

    static function drawLoginScreen($message = null)
    {
        $captcha = CCaptcha::getHtml();
        include "tpl/login.html";
        exit;
    }

    static function drawRegisterScreen($message = null)
    {
        $captcha = CCaptcha::getHtml();
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

    static function drawJournal()
    {
        $bodyName = "tpl/journal.html";
        include "tpl/main.html";
        exit;
    }
}