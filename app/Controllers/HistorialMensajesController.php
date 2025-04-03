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

        $historico = DB::ArtifyCrud();
		$historico->where("usuario", $funcionario);
		$historico->tableColFormatting("respuesta_bot", "readmore", array("length"=> 30,"showreadmore"=>true));
		$historico->tableColFormatting("fecha", "date",array("format" =>"d/m/Y"));
		$historico->setSearchCols(array("mensaje_usuario", "respuesta_bot", "fecha", "hora"));
		$historico->crudRemoveCol(array("id_historial_chat", "usuario"));
		$historico->setSettings("function_filter_and_search", true);
		$historico->tableHeading("Historial de mensajes");
		$historico->setSettings("searchbox", true);
		$historico->setSettings("addbtn", false);
		$historico->setSettings("editbtn", false);
		$historico->setSettings("delbtn", true);
        $historico->dbOrderBy("fecha desc");
		$historico->setLangData("no_data", "No se Encontraron Mensajes en el Historial");
		$render = $historico->dbTable("historial_chat")->render();

        View::render('HistorialMensajes', [
            'render' => $render
        ]);
    }
}