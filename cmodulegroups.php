<?php

include_once("cdatabase.php");
include_once("cmoduleacademicyear.php");
include_once("ctemplatecontroller.php");


class CModuleGroups {


    public function content()
    {
        $database = CDatabase::getInstance();

        if(isset($_GET['odt_import']))
        {
            $this->importOdt();
        }

        if(isset($_GET['txt_export']))
        {
            $this->txt_export();
        }


        if(isset($_GET['editname']))
        {
            $database->updateGroupName($_GET['editname'], $_POST['ajax_post_value']);
            exit;
        }

        if(isset($_GET['edit']))
        {
            $this->editGroupContent($_GET['edit']);
        }

        if(isset($_GET['delete']))
        {
            $database->deleteGroup($_GET['delete']);
            exit;
        }

        if($_GET['action'] == 'add')
        {
            $add_status = $database->addGroup($_POST['name'], CModuleAcademicYear::getId());

            if($add_status == false)
            {
                exit;
            }

            $group = $database->getGroupFromName($_POST['name'], CModuleAcademicYear::getId());

            $groupJson['id'] = $group['id'];
            $groupJson['name'] = $group['name'];

            echo json_encode($groupJson);
            exit;
        }

        $groups = $database->getGroups(CModuleAcademicYear::getId());

        $addTemplate['isAddTemplate'] = true;
        $addTemplate['id'] = '::id::';
        $addTemplate['name'] = '::name::';

        $groups[] = $addTemplate;

        CTemplateController::drawGroups($groups);
    }

    private function editGroupContent($idGroup)
    {
        $database = CDatabase::getInstance();

        if(isset($_GET['studentedit']))
        {
            if(isset($_POST['name']))
            {
                $database->updateStudent($_POST['name'] ,$_GET['studentedit']);
            }
            else
            {
                $group = $database->getGroup($idGroup);
                $student = $database->getStudent($_GET['studentedit']);
                CTemplateController::drawEditStudent($group, $student);
            }
        }

        if(isset($_GET['studentdelete']))
        {
            $database->deleteStudent($_GET['studentdelete']);
        }

        if($_GET['action'] == 'add')
        {
            $database->addStudent($_POST['name'], $idGroup);
        }

        $group = $database->getGroup($idGroup);
        $students = $database->getStudents($idGroup);

        CTemplateController::drawStudents($group, $students);
    }

    private function importOdt()
    {

        $zip = new ZipArchive();
        $zip->open($_FILES['odt_file']['tmp_name']);
        $index = $zip->locateName("content.xml");
        $xmlContent = $zip->getFromIndex($index);
        $zip->close();
        $xml = DOMDocument::loadXML($xmlContent, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
        $strContent = strip_tags($xml->saveXML());

        $database = CDatabase::getInstance();

        $countTags = 0;
        $posStartWord = -1;
        $mode = "";
        $groupId = -1;
        for($i=0;$i<strlen($strContent);$i++)
        {
            if($strContent[$i] == '#' || $i == strlen($strContent) - 1)
            {
                if($mode == "group")
                {
                    $mode = "";

                    $groupName = mb_substr($strContent, $posStartWord , $i - $posStartWord);;

                    $database->addGroup($groupName, CModuleAcademicYear::getId());
                    $groupId = $database->getInsertId();
                }

                if($mode == "student")
                {
                    $mode = "";

                    if($groupId == -1)
                        continue;

                    $studentName = mb_substr($strContent, $posStartWord , $i - $posStartWord);
                    $database->addStudent($studentName, $groupId);
                }

                $countTags++;
                continue;
            }

            if($countTags == 1)
            {
                $posStartWord = $i;
                $mode = "student";
            }

            if($countTags == 2)
            {
                $posStartWord = $i;
                $mode = "group";
            }

            $countTags = 0;
        }

    }

    private function txt_export()
    {
        $content = "";

        $database = CDatabase::getInstance();
        $groups = $database->getGroups(CModuleAcademicYear::getId());

        foreach($groups as $group)
        {
            $content .= "\r\n##" . $group['name'] . "\r\n";

            $students = $database->getStudents($group['id']);

            foreach($students as $student)
            {
                $content .= "#" . $student['name'] . "\r\n";
            }
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=groups.txt');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . strlen($content));

        print($content);

        exit();
    }


}