<?php

namespace App\Controllers;

use App\core\SessionManager;
use App\core\Token;
use App\core\DB;
use App\core\Request;
use App\core\View;
use App\core\Redirect;
use Docufy;

class ConfiguracionController
{
    public $token;

    public function __construct()
    {
        SessionManager::startSession();
        $Sesusuario = SessionManager::get('usuario');
        if (!isset($Sesusuario)) {
            Redirect::to("login");
        }
        $this->token = Token::generateFormToken('send_message');
        
    }
    public function index()
    {
        $settings["script_url"] = $_ENV['URL_ArtifyCrud'];
        $_ENV["url_artify"] = "artify/funcion_configuracion.php";
        $settings["url_artify"] = $_ENV["url_artify"];
        $settings["downloadURL"] = $_ENV['DOWNLOAD_URL'];
        $settings["hostname"] = $_ENV['DB_HOST'];
        $settings["database"] = $_ENV['DB_NAME'];
        $settings["username"] = $_ENV['DB_USER'];
        $settings["password"] = $_ENV['DB_PASS'];
        $settings["dbtype"] = $_ENV['DB_TYPE'];
        $settings["characterset"] = $_ENV["CHARACTER_SET"];

        $autoSuggestion = false;
        $artify = DB::ArtifyCrud(false, "", "", $autoSuggestion, $settings);
        $artify->addPlugin("bootstrap-colorpicker");
        $artify->setPK("id_configuracion");
        $queryfy = $artify->getQueryfyObj();
        $queryfy->where("id_configuracion", "1");
        $img = $queryfy->select("configuracion");
        
        $artify->addCallback("before_update", "before_actualizar_configuracion");
    
        $artify->addCallback("after_update", "after_actualizar_configuracion");
                    
        $artify->tableHeading('Configuración del Sistema');
    
        $html_template = '<div id="i57h" class="row pt-4">
                                <div id="iyql" class="col-md"><div class="form-group">
                                    <label id="iyvph" class="form-label">Logo Login:</label>
                                    <img class="logo_login w-25 img-thumbnail" src="'.$img[0]["logo_login"].'">
                                    <span id="i6jpb" class="editable">{logo_login}</span>
                                    <p class="artify_help_block help-block form-text with-errors"></p>
                                </div>
                            </div>
                            <div id="irlr1" class="col-md">
                                <div class="form-group">
                                    <label id="i9t5d" class="form-label">Logo Panel:</label>
                                    <img class="logo_panel w-25 mb-3 img-thumbnail" src="'.$img[0]["logo_panel"].'">
                                    <span id="iywri" class="editable">{logo_panel}</span>
                                    <p class="artify_help_block help-block form-text with-errors"></p>
                                    </div>
                                </div>
                            <div id="ijhw1" class="col-md">
                                <div class="form-group">
                                <label id="i3vek" class="form-label">Título Sistema:</label>
                                <span id="ifa97" class="editable">{titulo_sistema}</span>
                                <p class="artify_help_block help-block form-text with-errors"></p>
                                </div>
                            </div>
                            <div id="ijhw1" class="col-md">
                                <div class="form-group">
                                <label id="i3vek" class="form-label">Color de Fondo Menu Panel:</label>
                                <span id="ifa97" class="editable">{color_fondo_menu_panel}</span>
                                <p class="artify_help_block help-block form-text with-errors"></p>
                                </div>
                            </div>
                        </div>';
        $artify->set_template($html_template);
    
        $artify->setSettings('required', true);
    
        $artify->setSettings('hideAutoIncrement', true);
    
        $artify->setSettings('encryption', true);
    
        $artify->fieldTypes("logo_login", "FILE_NEW");
    
        $artify->fieldTypes("logo_panel", "FILE_NEW");
    
        $artify->fieldTypes("titulo_sistema", "input");

        $artify->fieldCssClass("color_fondo_menu_panel", array("color_fondo_menu_panel"));
    
        $artify->formFields(array("logo_login", "logo_panel", "titulo_sistema", "color_fondo_menu_panel"));
        
        $artify->setSettings('template', 'template_configuracion');
        $render = $artify->dbTable('configuracion')->render("editform", array("id" => "1"));
        $color = $artify->loadPluginJsCode("bootstrap-colorpicker",".color_fondo_menu_panel");

        View::render('configuracion', ['render' => $render, 'color' => $color]);
    }
}