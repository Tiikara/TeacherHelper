<?php

include_once('libs/recaptchalib.php');

class CCaptcha {


    static public function getHtml()
    {
        return recaptcha_get_html(self::$publickey, null);
    }

    static public function isValid()
    {
        if(isset($_POST['recaptcha_response_field']))
        {
            $resp = recaptcha_check_answer (self::$privatekey,
                $_SERVER["REMOTE_ADDR"],
                $_POST["recaptcha_challenge_field"],
                $_POST["recaptcha_response_field"]);

            return $resp->is_valid;
        }

        return false;
    }

    // Ключи для сервиса google reCaptcha. Получить ключи: https://www.google.com/recaptcha/admin
    static private $publickey = "6LcH0vUSAAAAACWptTRDGY1y0fCevYfTiLzsVRx_";
    static private $privatekey = "6LcH0vUSAAAAAC9Fbnm10dHPwOL2vY84Lqj8xpFL";
}