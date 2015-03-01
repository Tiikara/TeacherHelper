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

    /**
     * Соединение с базой данных
     */
    public function connect()
    {
        $this->sqldatabase->connect();
    }

    /**
     * Возвращает id пользователя. Если таковой не найден, то CDatabase::undefined_result.
     *
     * @param string $name Имя пользователя
     * @param string $md5hash Хешированный пароль
     *
     * @return int
     */
    public function getIdUser($name, $md5hash)
    {
        $sql_res = $this->sqldatabase->executeQuery("SELECT id FROM teachers WHERE login='$name' AND md5hash='$md5hash'");

        if($sql_res == CSqlDatabase::sqlerror || $sql_res->getCountRows() == 0)
            return CDatabase::undefined_result;

        $res_array = $sql_res->getRow();

        return $res_array['id'];
    }

    /**
     * Регистрация пользователя в базе данных
     *
     * @param $name string Имя пользователя
     * @param $md5hash string Хешированный пароль
     * @return bool
     */
    public function registerUser($name, $md5hash)
    {
        $sql_res = $this->sqldatabase->executeQuery("INSERT INTO teachers (login, md5hash) VALUES ('$name','$md5hash')");

        if($sql_res == CSqlDatabase::sqlerror)
            return false;

        return true;
    }

    public function getIdAcademicYear($year, $semester, $idTeacher)
    {
        $sql_res = $this->sqldatabase->executeQuery("SELECT id FROM academicyear WHERE year=$year AND semester=$semester AND id_teacher=$idTeacher");

        if($sql_res == CSqlDatabase::sqlerror || $sql_res->getCountRows() == 0)
            return CDatabase::undefined_result;

        $res_array = $sql_res->getRow();

        return $res_array['id'];
    }

    public function addAcademicYear($year, $semester, $idTeacher)
    {
        $sql_res = $this->sqldatabase->executeQuery("INSERT INTO academicyear (year, semester, id_teacher) VALUES ($year, $semester, $idTeacher)");

        if($sql_res == CSqlDatabase::sqlerror)
            return false;

        return true;
    }

    public function addGroup($name, $idAcademicYear)
    {
        $sql_res = $this->sqldatabase->executeQuery("INSERT INTO groups (name, id_academicyear) VALUES ('$name', $idAcademicYear)");

        if($sql_res == CSqlDatabase::sqlerror)
            return false;

        return true;
    }

    public function getGroup($idGroup)
    {
        $sql_res = $this->sqldatabase->executeQuery("SELECT id, name FROM groups WHERE id=$idGroup");

        return $sql_res->getRow();
    }

    public function getGroups($idAcademicYear)
    {
        $sql_res = $this->sqldatabase->executeQuery("SELECT id, name FROM groups WHERE id_academicyear=$idAcademicYear");

        return $sql_res->getArrayRows();
    }

    public function getStudents($idGroup)
    {
        $sql_res = $this->sqldatabase->executeQuery("SELECT id, name FROM students WHERE id_group=$idGroup");

        return $sql_res->getArrayRows();
    }

    public function addStudent($name, $idGroup)
    {
        $sql_res = $this->sqldatabase->executeQuery("INSERT INTO students (name, id_group) VALUES ('$name', $idGroup)");

        if($sql_res == CSqlDatabase::sqlerror)
            return false;

        return true;
    }

    public function getStudent($idStudent)
    {
        $sql_res = $this->sqldatabase->executeQuery("SELECT id, name FROM students WHERE id=$idStudent");

        return $sql_res->getRow();
    }

    public function updateStudent($name, $idStudent)
    {
        $this->sqldatabase->executeQuery("UPDATE students SET name='$name' WHERE id=$idStudent");
    }

    public function deleteGroup($idGroup)
    {
        $this->sqldatabase->executeQuery("DELETE FROM groups WHERE id=$idGroup");
    }

    public function deleteStudent($idStudent)
    {
        $this->sqldatabase->executeQuery("DELETE FROM students WHERE id=$idStudent");
    }

    public function addDiscipline($name, $idAcademicYear)
    {
        $sql_res = $this->sqldatabase->executeQuery("INSERT INTO discipline (name, id_academicyear) VALUES ('$name', $idAcademicYear)");

        if($sql_res == CSqlDatabase::sqlerror)
            return false;

        return true;
    }

    public function getDisciplines($idAcademicYear)
    {
        $sql_res = $this->sqldatabase->executeQuery("SELECT id, name FROM discipline WHERE id_academicyear=$idAcademicYear");

        return $sql_res->getArrayRows();
    }

    public function getDiscipline($idDiscipline)
    {
        $sql_res = $this->sqldatabase->executeQuery("SELECT id, name FROM discipline WHERE id=$idDiscipline");

        return $sql_res->getRow();
    }

    public function deleteDiscipline($idDiscipline)
    {
        $this->sqldatabase->executeQuery("DELETE FROM discipline WHERE id=$idDiscipline");
    }

    public function getGroupsRelatedDiscipline($idDiscipline)
    {
        $sql_res = $this->sqldatabase->executeQuery("SELECT groups.id AS id, groups.name AS name
                                                      FROM groups, discipline_groups
                                                      WHERE discipline_groups.id_discipline=$idDiscipline
                                                            AND groups.id=discipline_groups.id_group");

        return $sql_res->getArrayRows();
    }

    public function connectDisciplineToGroup($idDiscipline, $idGroup)
    {
        $sql_res = $this->sqldatabase->executeQuery("INSERT INTO discipline_groups (id_discipline, id_group) VALUES ($idDiscipline, $idGroup)");

        if($sql_res == CSqlDatabase::sqlerror)
            return false;

        return true;
    }

    public function disconnectDisciplineFromGroup($idDiscipline, $idGroup)
    {
        $sql_res = $this->sqldatabase->executeQuery("DELETE FROM discipline_groups WHERE id_discipline=$idDiscipline AND id_group=$idGroup");

        if($sql_res == CSqlDatabase::sqlerror)
            return false;

        return true;
    }

    const undefined_result = -1;

    static protected $instance;

    /** @var $name CSqlDatabase */
    private $sqldatabase;
}