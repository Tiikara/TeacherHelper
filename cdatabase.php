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
     * Возвращает id пользователя. Если таковой не найден, то -1.
     *
     * @param string $name
     * @param string $md5hash
     *
     * @return int
     */
    public function tryLogin($name, $md5hash)
    {
        $sql_res = $this->sqldatabase->executeQuery("SELECT id FROM Teachers WHERE Teachers.name=$name AND Teachers.md5hash=$md5hash");

        $res_array = $sql_res->getArrayRows();

        if($sql_res->getCountRows() == 0)
            return -1;

        return $res_array['id'];
    }

    static protected $instance;

    /** @var $name CSqlDatabase */
    private $sqldatabase;
}