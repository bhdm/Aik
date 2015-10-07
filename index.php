<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once 'Vendor/mpdf60/mpdf.php';

require_once 'functions.php';
    use Model\Driver;

    function autoload($className)
    {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        require $fileName;
    }
    spl_autoload_register('autoload');


    if ($_GET['pdf'] == 1){
        $htmlOut = file_get_contents('http://aik.loc/index.php');
        $mpdf=new mPDF('utf-8','A4-L','','',22,14,12,23,9,9);
//        $mpdf->charset_in = 'utf-8';
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->list_indent_first_level = 0;
        $mpdf->WriteHTML($htmlOut);
        $mpdf->Output("filename.pdf",'I');
    }else{
        # Далее код
        $db = new Driver();
        $db->connect();
//    $data = $db->find('group', 110);
        $groups = $db->findAll('group', 110);
        include 'Views/main.php';
    }

