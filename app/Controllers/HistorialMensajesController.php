<?php

namespace App\Controllers;

use App\core\SessionManager;
use App\core\Token;
use App\core\Request;
use App\core\View;
use App\core\Redirect;
use App\core\DB;

class HistorialMensajesController
{
    public $token;

    public function __construct(){
        SessionManager::startSession();
        $Sesusuario = SessionManager::get('usuario');
        if (!isset($Sesusuario)) {
            Redirect::to("login");
        }
        $this->token = Token::generateFormToken('send_message');  
    }

    public function index()
    {
        $funcionario = $_SESSION["usuario"][0]["id"];

        $settings["script_url"] = $_ENV['URL_ArtifyCrud'];
        $_ENV["url_artify"] = "artify/artifycrud.php";
        $settings["url_artify"] = $_ENV["url_artify"];
        $settings["downloadURL"] = $_ENV['DOWNLOAD_URL'];
        $settings["hostname"] = $_ENV['DB_HOST'];
        $settings["database"] = $_ENV['DB_NAME'];
        $settings["username"] = $_ENV['DB_USER'];
        $settings["password"] = $_ENV['DB_PASS'];
        $settings["dbtype"] = $_ENV['DB_TYPE'];
        $settings["characterset"] = $_ENV["CHARACTER_SET"];

        $autoSuggestion = true;
        $artify = DB::ArtifyCrud(false, "", "", $autoSuggestion, $settings);
		$artify->where("usuario", $funcionario);
		$artify->tableColFormatting("respuesta_bot", "readmore", array("length"=> 30, "showreadmore"=> true));
		$artify->tableColFormatting("fecha", "date",array("format" =>"d/m/Y"));
		$artify->setSearchCols(array("mensaje_usuario", "respuesta_bot", "fecha", "hora"));
		$artify->crudRemoveCol(array("id_historial_chat", "usuario"));
		$artify->setSettings("function_filter_and_search", true);
		$artify->tableHeading("Historial de Mensajes");
		$artify->setSettings("searchbox", true);
        $artify->setSettings("showAllSearch", false);
		$artify->setSettings("addbtn", false);
		$artify->setSettings("editbtn", false);
		$artify->setSettings("delbtn", true);
        $artify->dbOrderBy("fecha desc");
		$artify->setLangData("no_data", "No se Encontraron Mensajes en el Historial");
		$render = $artify->dbTable("historial_chat")->render();

        View::render('HistorialMensajes', [
            'render' => $render
        ]);
    }
}