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

    static function drawJournal()
    {
        self::drawMain("tpl/journal.html");
    }

    static private function drawMain($bodyName)
    {
        include "tpl/main.html";
        exit;
    }
}