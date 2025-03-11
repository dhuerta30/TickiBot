<?php

    namespace App\Controllers;

    use App\core\SessionManager;
    use App\core\Token;
    use App\core\DB;
    use App\core\Request;
    use App\core\View;
    use App\core\Redirect;
    use Docufy;

    class InventarioController
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
        public function Inventario_de_Productos()
        {
                $settings["script_url"] = $_ENV['URL_ArtifyCrud'];
                $_ENV["url_artify"] = "artify/funcion_inventario.php";
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

                $artify->bulkCrudUpdate("tipo", "text", array("data-some-attr" =>"some-dummy-val"));
                $artify->bulkCrudUpdate("precio", "text", array("data-some-attr" =>"some-dummy-val"));
            
                            $artify->addCallback("after_insert", "after_insert_inventario");
                        
                            $artify->addCallback("before_update", "before_update_inventario");
                        
                            $artify->addCallback("after_update", "after_update_inventario");
                        
                            $artify->addCallback("before_delete", "before_delete_inventario");
                        
                            $artify->addCallback("after_delete", "after_delete_inventario");
                        
                            $artify->addCallback("before_delete_selected", "before_delete_selected_inventario");
                        
                            $artify->addCallback("before_delete_selected", "before_delete_selected_inventario");
                        
                            $artify->addCallback("before_switch_update", "before_switch_update_inventario");
                        
                            $artify->addCallback("after_switch_update", "after_switch_update_inventario");
                        
                            $artify->addCallback("before_select", "before_select_inventario");
                        
                            $artify->addCallback("after_select", "after_select_inventario");
                        
                            $artify->addCallback("format_table_data", "format_table_data_inventario");
                        
                            $artify->addCallback("format_table_col", "format_table_col_inventario");
                        
                    $artify->setSearchCols(array("nombre_producto", "tipo", "cantidad", "total_de_ventas", "nuevos_ingresos", "stock_actual", "precio"));
                
                    $artify->crudTableCol(array("nombre_producto", "tipo", "cantidad", "total_de_ventas", "nuevos_ingresos", "stock_actual", "precio"));
                
                                        $artify->fieldTypes("nombre_producto", "input");
                                    
                                        $artify->fieldTypes("tipo", "input");
                                    
                                        $artify->fieldTypes("cantidad", "input");
                                    
                                        $artify->fieldTypes("precio", "input");
                                    
                                        $artify->fieldTypes("observacion", "textarea");
                                    
                    $artify->formFields(array("nombre_producto", "tipo", "cantidad", "precio", "observacion"));
                
                    $artify->editFormFields(array("nombre_producto", "tipo", "precio", "observacion"));
                
                            $artify->colRename("tipo", "Tipo Producto");
                        
                            $artify->colRename("cantidad", "Cantidad en Bodega");
                        
                        $artify->fieldNotMandatory("observacion");
                    
                            $artify->fieldRenameLable("tipo", "Tipo Producto");
                        
                            $artify->fieldRenameLable("cantidad", "Cantidad En Bodega");
                        
            $artify->tableHeading('<i class="fa fa-book"></i> Inventario de Productos');
        
            $artify->dbOrderBy("id_inventario", "ASC");
        
            $artify->currentPage(1);

            $artify->tableColFormatting("stock_actual", "formula", array("type" => "decimal", "decimal_separator" => ',', "thousands_separator" => '.'));
        
            $artify->setSettings("actionBtnPosition", "right");
        
                $artify->setSettings('editbtn', true);
            
                $artify->setSettings('delbtn', true);
            
                $artify->buttonHide("submitBtnSaveBack");
            
                $artify->setSettings('excelBtn', true);
            
            $artify->formDisplayInPopup();
        
            $artify->setSettings('inlineEditbtn', false);
        
            $artify->setSettings('hideAutoIncrement', false);
        
            $artify->setSettings('actionbtn', true);
        
            $artify->setSettings('function_filter_and_search', true);
        
            $artify->setSettings('searchbox', true);
        
            $artify->setSettings('clonebtn', false);
        
            $artify->setSettings('checkboxCol', false);
            $artify->setSettings('deleteMultipleBtn', false);
        
        $artify->setSettings('refresh', false);
    
            $artify->setSettings('addbtn', true);
        
            $artify->setSettings('encryption', true);
        
            $artify->setSettings('required', true);
        
            $artify->setSettings('pagination', true);
        
            $artify->setSettings('numberCol', false);
        
            $artify->setSettings('recordsPerPageDropdown', true);
        
            $artify->setSettings('totalRecordsInfo', true);
        
            $artify->setLangData('no_data', 'No se encontraron productos en el Inventario');
        
            $artify->recordsPerPage(10);
        
        $artify->setSettings('template', 'template_inventario');
        $render = $artify->dbTable('inventario')->render();

        View::render('inventario', ['render' => $render]);
    }
}