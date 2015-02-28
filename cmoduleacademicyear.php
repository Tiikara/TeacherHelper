<?php

include_once("cdatabase.php");
include_once("clogin.php");
include_once("ctemplatecontroller.php");

class CModuleAcademicYear {


    static public function getId()
    {
        return self::$id;
    }

    public function init()
    {
        $academ_date = $this->calcAcademicDate();

        $database = CDatabase::getInstance();

        if($_GET['action'] == 'createacademicyear')
        {
            $database->addAcademicYear($academ_date['year'], $academ_date['semester'], CLogin::getIdTeacher());
        }

        $idAcademicYear = $database->getIdAcademicYear($academ_date['year'], $academ_date['semester'], CLogin::getIdTeacher());

        if($idAcademicYear == CDatabase::undefined_result)
        {
            CTemplateController::drawAcademicYear();
        }

        self::$id = $idAcademicYear;
    }

    private function calcAcademicDate()
    {
        $array_academ = array();

        $cur_date = getdate();

        if($cur_date['mon'] > 9 ||  $cur_date['mon'] < 2)
        {
            $semester = 1;
        }
        else
        {
            $semester = 2;
        }

        $array_academ['year'] = $cur_date['year'];
        $array_academ['semester'] = $semester;

        return $array_academ;
    }


    static private $id;
}