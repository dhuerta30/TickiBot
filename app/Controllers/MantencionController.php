<?php

namespace App\Controllers;

use App\core\SessionManager;
use App\core\Token;
use App\core\Request;
use App\core\View;
use App\core\Redirect;
use App\core\DB;

class MantencionController
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
        $artify->addPlugin("chosen");
        $artify->setSettings("function_filter_and_search", true);
        $artify->setSearchCols(array(
            "nombre_funcionario", 
            "modelo",
            "marca_equipo", 
            "hora_de_retiro", 
            "fecha_mantencion", 
            "servicio", 
            "tipo_disco_duro", 
            "cantidad_ram",
            "procesador",
            "tecnico_encargado",
            "ip",
            "observaciones",
            "estado"
        ));
        $artify->tableHeading("Mantención Equipos");
        $artify->setSettings("searchbox", true);
        $artify->setSettings("actionFilterPosition", "top");
        $artify->fieldGroups("group1", array("nombre_funcionario","modelo", "marca_equipo", "hora_de_retiro"));
        $artify->fieldGroups("group2", array("fecha_mantencion","servicio", "tipo_disco_duro", "cantidad_ram"));
        $artify->fieldGroups("group3", array("procesador","tecnico_encargado", "ip"));
        $artify->buttonHide("submitBtnSaveBack");
        $artify->fieldCssClass("nombre_funcionario", array("nombre_funcionario"));
        $artify->fieldCssClass("tecnico_encargado", array("tecnico_encargado"));
        $artify->fieldCssClass("marca_equipo", array("marca_equipo"));
        $artify->fieldTypes("estado", "select");
        $artify->fieldDataBinding("estado", array("Entregado" => "Entragado", "En Proceso" => "En Proceso"), "", "", "array");

        $artify->fieldTypes("marca_equipo", "select");
        $artify->fieldDataBinding("marca_equipo", array(
            "Dell" => "Dell",
            "HP" => "HP",
            "Lenovo" => "Lenovo",
            "Acer" => "Acer",
            "Asus" => "Asus",
            "Apple" => "Apple",
            "MSI" => "MSI",
            "Samsung" => "Samsung",
            "Toshiba" => "Toshiba",
            "Sony" => "Sony (VAIO)",
            "Razer" => "Razer",
            "Alienware" => "Alienware",
            "Gigabyte" => "Gigabyte",
            "Microsoft" => "Microsoft (Surface)",
            "Huawei" => "Huawei",
            "Xiaomi" => "Xiaomi",
            "Chuwi" => "Chuwi",
            "Panasonic" => "Panasonic (Toughbook)",
            "Fujitsu" => "Fujitsu",
            "LG" => "LG"
        ), "", "", "array");

        $artify->fieldTypes("tecnico_encargado", "select");
        $artify->fieldDataBinding("tecnico_encargado", array(
            "Sergio Andrés Concha Llanos" => "Sergio Andrés Concha Llanos",
            "Juan Pablo Alvarez Avalos" => "Juan Pablo Alvarez Avalos",
            "Leonardo Antonio Martinez Vera" => "Leonardo Antonio Martinez Vera",
            "Franco Matias Carrasco Azocar" => "Franco Matias Carrasco Azocar",
            "Fabian Geovanni Pacheco Villalobos" => "Fabian Geovanni Pacheco Villalobos",
            "Elena Garrido Santibañez" => "Elena Garrido Santibañez",
            "Jorge Nicolas Berrios Cornejo" => "Jorge Nicolas Berrios Cornejo"
        ), "", "","array");

        $artify->formFieldValue("estado", "En Proceso");

        $artify->addFilter("FechaFilter", "Filtrar por Fecha Mantención", "fecha_mantencion", "date");
        $artify->setFilterSource("FechaFilter", "mantencion_equipos", "fecha_mantencion", "fecha_mantencion as pl", "db");

        $artify->fieldHideLable("estado");
        $artify->fieldDataAttr("estado", array("style"=>"display:none"));

        $artify->relatedData('nombre_funcionario','usuario','id','nombre');

        $artify->setSettings("printBtn", true);
        $artify->setSettings("pdfBtn", true);
        $artify->setSettings("csvBtn", true);
        $artify->setSettings("excelBtn", true);
        $artify->setLangData("no_data", "No se encontraron mantenciones");

        $artify->crudRemoveCol(array("id_mantencion_equipos"));
        $artify->addCallback("before_insert", "insertar_mantencion_equipos");
        $render = $artify->dbTable("mantencion_equipos")->render();
        $chosen = $artify->loadPluginJsCode("chosen",".nombre_funcionario, .tecnico_encargado, .marca_equipo");
        View::render('Mantencion', [
            'render' => $render,
            'chosen' => $chosen
        ]);
    }
}