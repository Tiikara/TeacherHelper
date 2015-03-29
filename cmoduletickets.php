<?php

include_once("cdatabase.php");
include_once("cmoduleacademicyear.php");
include_once("ctemplatecontroller.php");


class CModuleTickets {

    public function content()
    {
        $database = CDatabase::getInstance();

        if(isset($_GET['disc']))
        {
            $this->showQuestions($_GET['disc']);
        }

        $disciplines = $database->getDisciplines(CModuleAcademicYear::getId());

        CTemplateController::drawTicketsSelectDiscipline($disciplines);
    }

    private function showQuestions($idDiscipline)
    {
        $database = CDatabase::getInstance();

        if(isset($_GET['report']))
        {
            $this->showReport($idDiscipline);
        }

        if(isset($_GET['theme']))
        {
            $this->editThemes($idDiscipline);
        }

        if($_GET['action'] == 'add')
        {
            $add_status = $database->addQuestion($_POST['theme_id'], $_POST['description'], $_POST['difficulty']);

            if($add_status == false)
            {
                exit;
            }

            $question = $database->getLastAddedQuestion();

            $questionJson['id'] = $question['id'];
            $questionJson['name_theme_question'] = $question['name_theme_question'];
            $questionJson['description'] = $question['description'];
            $questionJson['difficulty'] = $question['difficulty'];

            echo json_encode($questionJson);
            exit;
        }

        if(isset($_GET['delete']))
        {
            $database->deleteQuestion($_GET['delete']);
            exit;
        }

        $questions = $database->getQuestions($idDiscipline);

        $addTemplate['isAddTemplate'] = true;
        $addTemplate['id'] = '::id::';
        $addTemplate['name_theme_question'] = '::name_theme_question::';
        $addTemplate['description'] = '::description::';
        $addTemplate['difficulty'] = '::difficulty::';

        $questions[] = $addTemplate;

        $themes = $database->getThemes($idDiscipline);

        CTemplateController::drawTicketsQuestions($idDiscipline, $questions, $themes);
    }

    private function editThemes($idDiscipline)
    {
        $database = CDatabase::getInstance();

        if(isset($_GET['edit']))
        {
            $database->updateNameTheme($_GET['edit'], $_POST['ajax_post_value']);
            exit;
        }

        if(isset($_GET['delete']))
        {
            $database->deleteTheme($_GET['delete']);
            exit;
        }

        if($_GET['action'] == 'add')
        {
            $add_status = $database->addTheme($_POST['name'], $idDiscipline);

            if($add_status == false)
            {
                exit;
            }

            $theme = $database->getThemeFromName($_POST['name'], $idDiscipline);

            $themeJson['id'] = $theme['id'];
            $themeJson['name'] = $theme['name'];

            echo json_encode($themeJson);
            exit;
        }

        $themes = $database->getThemes($idDiscipline);

        $addTemplate['isAddTemplate'] = true;
        $addTemplate['id'] = '::id::';
        $addTemplate['name'] = '::name::';

        $themes[] = $addTemplate;

        CTemplateController::drawTicketsEditTheme($idDiscipline, $themes);
    }

    private function showReport($idDiscipline)
    {
        $database = CDatabase::getInstance();

        $questions = $database->getQuestions($idDiscipline);

        $tickets = array();

        $allDifficulty = 0;
        foreach($questions as $question)
        {
            $allDifficulty += $question['difficulty'];
        }

        if(count($questions) == 0)
        {
            $averageDifficulty = 0;
        }
        else
        {
            $averageDifficulty = 2 * $allDifficulty / count($questions);
        }

        while(count($questions) != 0 && count($questions) != 1)
        {
            $currQuestion = $questions[0];
            unset($questions[0]);
            $questions = array_values($questions);

            $minError = 99999;
            $idMinQuestion = -1;
            $minAllDifficulty = 0;

            for($i=0;$i<count($questions);$i++)
            {
                if($questions[$i]['id_theme_question'] == $currQuestion['id_theme_question'])
                    continue;

                $ticketDifficulty = $questions[$i]['difficulty'] + $currQuestion['difficulty'];

                $errorDifficulty = abs($ticketDifficulty - $averageDifficulty);

                if($errorDifficulty < $minError)
                {
                    $minError = $errorDifficulty;
                    $idMinQuestion = $i;
                    $minAllDifficulty = $ticketDifficulty;
                }
            }

            if($idMinQuestion == -1)
                break;

            $ticket['questions'][0] = $currQuestion;
            $ticket['questions'][1] = $questions[$idMinQuestion];
            $ticket['difficulty'] = $minAllDifficulty;

            $tickets[] = $ticket;

            unset($questions[$idMinQuestion]);
            $questions = array_values($questions);

        }


        CTemplateController::drawTicketsReport($tickets);
    }

}