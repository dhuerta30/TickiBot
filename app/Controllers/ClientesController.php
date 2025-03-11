<?php

namespace App\Controllers;

use App\core\SessionManager;
use App\core\Token;
use App\core\DB;
use App\core\Request;
use App\core\View;
use App\core\Redirect;
use Docufy;

class ClientesController {
    
    public $token;

    public function __construct(){
        SessionManager::startSession();
        $Sesusuario = SessionManager::get('usuario');
        if (!isset($Sesusuario)) {
            Redirect::to("login");
        }
        $this->token = Token::generateFormToken('send_message');        
    }

    public function Modulo_de_Clientes() {

        $settings["script_url"] = $_ENV['URL_ArtifyCrud'];
        $_ENV["url_artify"] = "artify/funcion_clientes.php";
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
        $queryfy = $artify->getQueryfyObj();
            
        $artify->addCallback("before_insert", "before_insert_clientes");
    
        $artify->addCallback("before_update", "before_update_clientes");
    
        $artify->addCallback("before_delete", "before_delete_clientes");
                        
        $artify->setSearchCols(array("nombre", "rut", "direccion", "correo", "fono"));
    
        $artify->crudTableCol(array("nombre", "rut", "direccion", "correo", "fono"));
                
        $artify->fieldTypes("nombre", "input");
    
        $artify->fieldTypes("rut", "input");
    
        $artify->fieldTypes("direccion", "input");
    
        $artify->fieldTypes("correo", "input");
    
        $artify->fieldTypes("fono", "input");
                                    
        $artify->formFields(array("nombre", "rut", "direccion", "correo", "fono"));
    
        $artify->editFormFields(array("nombre", "rut", "direccion", "correo", "fono"));

        $artify->addFilter('filterAddnombre', 'Filtrar por Nombre', 'nombre', 'dropdown');
        $artify->setFilterSource('filterAddnombre', 'clientes', 'nombre', 'nombre as pl', 'db');
    
        $artify->addFilter('filterAddrut', 'Filtrar por Rut', 'rut', 'text');
        $artify->setFilterSource('filterAddrut', '', '', '', '');
                            
        $html_template = '<div class="row pt-4" id="it5m"><!-- Las columnas se colocarán aquí --><div class="col-md" id="i7gi"><div class="form-group"><label class="form-label" id="ienc6">Nombre:</label><span class="editable" id="ibsso">{nombre}</span><p class="artify_help_block help-block form-text with-errors"></p></div></div><div class="col-md" id="iik1a"><div class="form-group"><label class="form-label" id="ifb1g">Rut:</label><span class="editable" id="io4g6">{rut}</span><p class="artify_help_block help-block form-text with-errors"></p></div></div><div class="col-md" id="iydyz"><div class="form-group"><label class="form-label" id="i7iu4">Dirección:</label><span class="editable" id="i8xqf">{direccion}</span><p class="artify_help_block help-block form-text with-errors"></p></div></div></div><div class="row pt-4" id="ibowe"><!-- Las columnas se colocarán aquí --><div class="col-md" id="idaqi"><div class="form-group"><label class="form-label" id="is7an">Correo:</label><span class="editable" id="iv8hi">{correo}</span><p class="artify_help_block help-block form-text with-errors"></p></div></div><div class="col-md" id="iub91"><div class="form-group"><label class="form-label" id="il2zw">Fono:</label><span class="editable" id="id0zd">{fono}</span><p class="artify_help_block help-block form-text with-errors"></p></div></div></div>';
        $artify->set_template($html_template);
    
        $artify->tableHeading('Módulo de Clientes');
    
        $artify->setSettings("actionFilterPosition", "top");
    
        $artify->dbOrderBy("id_clientes", "ASC");
    
        $artify->currentPage(1);
    
        $artify->setSettings("actionBtnPosition", "right");
        
        $artify->setSettings('editbtn', true);
    
        $artify->setSettings('delbtn', true);
    
        $artify->buttonHide("submitBtnSaveBack");
    
        $artify->setSettings('excelBtn', true);
            
        $artify->formDisplayInPopup();
    
        $artify->setSettings('inlineEditbtn', false);
    
        $artify->setSettings('hideAutoIncrement', true);
    
        $artify->setSettings('actionbtn', true);
    
        $artify->setSettings('function_filter_and_search', true);
    
        $artify->setSettings('searchbox', true);
    
        $artify->setSettings('clonebtn', false);
    
        $artify->setSettings('checkboxCol', true);
        $artify->setSettings('deleteMultipleBtn', true);
        
        $artify->setSettings('refresh', false);

        $artify->setSettings('addbtn', true);
    
        $artify->setSettings('encryption', true);
    
        $artify->setSettings('required', true);
    
        $artify->setSettings('pagination', true);
    
        $artify->setSettings('numberCol', false);
    
        $artify->setSettings('recordsPerPageDropdown', true);
    
        $artify->setSettings('totalRecordsInfo', true);
    
        $artify->setLangData('no_data', 'No se encontraron Clientes');
    
        $artify->recordsPerPage(10);
        
        $artify->setSettings('template', 'template_clientes');
        $render = $artify->dbTable('clientes')->render();

        View::render('clientes', ['render' => $render]);
    }
}