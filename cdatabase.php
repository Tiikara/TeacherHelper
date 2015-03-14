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

    public function getScheduleOfDay($idAcademicYear, $dayOfWeek)
    {
        $sql_res = $this->sqldatabase->executeQuery("SELECT schedule.id AS id, schedule.num_lecture AS num_lecture,
                                                      schedule.type_alternation AS type_alternation,
                                                      discipline.name AS name_discipline, groups.name AS name_group,
                                                      type_lecture.name as name_type_lecture
                                                      FROM groups, discipline, discipline_groups, schedule, type_lecture, academicyear
                                                      WHERE academicyear.id=$idAcademicYear AND schedule.day_week=$dayOfWeek AND
                                                            discipline_groups.id=schedule.id_disc_group AND
                                                            discipline_groups.id_group=groups.id AND discipline_groups.id_discipline=discipline.id AND
                                                            type_lecture.id=schedule.id_type_lecture
                                                            ORDER BY schedule.num_lecture ASC");

        return $sql_res->getArrayRows();
    }

    public function getDisciplineGroups($idAcademicYear)
    {
        $sql_res = $this->sqldatabase->executeQuery("SELECT discipline_groups.id AS id, discipline.name AS name_discipline,
                                                     groups.name AS name_group
                                                     FROM academicyear, discipline_groups, discipline, groups
                                                     WHERE academicyear.id=$idAcademicYear AND
                                                     academicyear.id=discipline.id_academicyear AND academicyear.id=groups.id_academicyear AND
                                                     discipline.id=discipline_groups.id_discipline AND groups.id=discipline_groups.id_group");

        return $sql_res->getArrayRows();
    }

    public function getTypeLectures()
    {
        $sql_res = $this->sqldatabase->executeQuery("SELECT type_lecture.id AS id, type_lecture.name AS name FROM type_lecture");

        return $sql_res->getArrayRows();
    }

    public function addSchedule($idAcademicYear, $dayOfWeek, $numLecture, $typeAlternation, $idDiscGroup, $idTypeLecture)
    {
        $sql_res = $this->sqldatabase->executeQuery("INSERT INTO schedule (id_academicyear, day_week, num_lecture, type_alternation, id_disc_group, id_type_lecture)
                                                    VALUES ($idAcademicYear, $dayOfWeek, $numLecture, $typeAlternation, $idDiscGroup, $idTypeLecture)");

        if($sql_res == CSqlDatabase::sqlerror)
            return false;

        return true;
    }

    public function deleteSchedule($idSchedule)
    {
        $sql_res = $this->sqldatabase->executeQuery("DELETE FROM schedule WHERE schedule.id=$idSchedule");

        if($sql_res == CSqlDatabase::sqlerror)
            return false;

        return true;
    }

    public function getTasks($idDiscipline)
    {
        $sql_res = $this->sqldatabase->executeQuery("SELECT tasks.id AS id, tasks.description AS description, tasks.date_to AS date_to,
                                                      tasks.difficulty AS difficulty
                                                     FROM tasks
                                                     WHERE tasks.id_discipline=$idDiscipline");


        return $sql_res->getArrayRows();
    }

    public function addTask($idDiscipline, $description, $date_to, $difficulty)
    {
        $sql_res = $this->sqldatabase->executeQuery("INSERT INTO tasks (id_discipline, description, date_to, difficulty)
                                                    VALUES ($idDiscipline, '$description', '$date_to', $difficulty)");

        if($sql_res == CSqlDatabase::sqlerror)
            return false;

        return true;
    }

    public function deleteTask($idTask)
    {
        $sql_res = $this->sqldatabase->executeQuery("DELETE FROM tasks WHERE tasks.id=$idTask");

        if($sql_res == CSqlDatabase::sqlerror)
            return false;

        return true;
    }

    const undefined_result = -1;

    static protected $instance;

    /** @var $name CSqlDatabase */
    private $sqldatabase;
}