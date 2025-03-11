<?php

        namespace App\Controllers;

        use App\core\SessionManager;
        use App\core\Token;
        use App\core\DB;
        use App\core\View;
        use App\core\Redirect;
        use Docufy;

        class Controller
        {
            public $token;

            public function __construct()
            {
            }
            public function index()
            {
                    $artify = DB::ArtifyCrud();
                    $queryfy = $artify->getQueryfyObj();
                
                    $artify->tableHeading('nombre,apellido,fecha_nacimiento,adjunto');
                
                    $artify->setSettings('required', false);
                
                    $artify->setSettings('hideAutoIncrement', false);
                
                    $artify->setSettings('encryption', false);
                
                $artify->setSettings('template', 'template_');
                $render = $artify->dbTable('')->render("insertform");

                View::render('', ['render' => $render]);
            }

        }