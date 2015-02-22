<?php

class CTemplateController {

    static function drawLoginScreen($captcha)
    {
        include "tpl/login.html";
        exit;
    }

    static function drawMain($bodyName)
    {
        include "tpl/main.html";
        exit;
    }
}