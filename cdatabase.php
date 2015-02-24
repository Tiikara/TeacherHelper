<?php

include_once("csqldatabase.php");

class CDatabase {

    private function CDatabase()
    {
        $this->sqldatabase = new CSqlDatabase();
    }

    /**
     * @return CDatabase
     */
    public static function createInstance()
    {
        self::$instance = new self();
        return self::$instance;
    }

    /**
     * @return CDatabase
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    public function connect()
    {
        $this->sqldatabase->connect();
    }

    /**
     * Возвращает id пользователя. Если таковой не найден, то CDatabase::undefined_result.
     *
     * @param string $name
     * @param string $md5hash
     *
     * @return int
     */
    public function getIdUser($name, $md5hash)
    {
        $sql_res = $this->sqldatabase->executeQuery("SELECT id FROM teachers WHERE login='$name' AND md5hash='$md5hash'");

        if($sql_res == CSqlDatabase::sqlerror || $sql_res->getCountRows() == 0)
            return CDatabase::undefined_result;

        $res_array = $sql_res->getArrayRows();

        return $res_array['id'];
    }

    public function registerUser($name, $md5hash)
    {
        $sql_res = $this->sqldatabase->executeQuery("INSERT INTO teachers (login, md5hash) VALUES ('$name','$md5hash')");

        if($sql_res == CSqlDatabase::sqlerror)
            return false;

        return true;
    }

    const undefined_result = -1;

    static protected $instance;

    /** @var $name CSqlDatabase */
    private $sqldatabase;
}