<?php
            require_once dirname(__DIR__, 3) . "/vendor/autoload.php";

            // Cargar variables de entorno antes de iniciar la sesión
            $dotenv = DotenvVault\DotenvVault::createImmutable(dirname(__DIR__, 3));
            $dotenv->safeLoad();

            @session_name($_ENV["APP_NAME"]);
            @session_start();
            /*enable this for development purpose */
            //ini_set('display_startup_errors', 1);
            //ini_set('display_errors', 1);
            //error_reporting(-1);
            date_default_timezone_set(@date_default_timezone_get());
            define('ArtifyABSPATH', dirname(__FILE__) . '/');
            require_once ArtifyABSPATH . "config/config.php";
            spl_autoload_register('artifyAutoLoad');

            function artifyAutoLoad($class) {
                if (file_exists(ArtifyABSPATH . "classes/" . $class . ".php"))
                    require_once ArtifyABSPATH . "classes/" . $class . ".php";
            }

            if (isset($_REQUEST["artify_instance"])) {
                $fomplusajax = new ArtifyAjaxCtrl();
                $fomplusajax->handleRequest();
            }
                            function after_insert_inventario($data, $obj){

                                return $data;
                            }
                        
                            function before_update_inventario($data, $obj){

                                return $data;
                            }
                        
                            function after_update_inventario($data, $obj){

                                return $data;
                            }
                        
                            function before_delete_inventario($data, $obj){

                                return $data;
                            }
                        
                            function after_delete_inventario($data, $obj){

                                return $data;
                            }
                        
                            function before_delete_selected_inventario($data, $obj){
                            
                                return $data;
                            }
                        
                            function before_switch_update_inventario($data, $obj){
                            
                                return $data;
                            }
                        
                            function after_switch_update_inventario($data, $obj){

                                return $data;
                            }
                        
                            function before_select_inventario($data, $obj){
                            
                                return $data;
                            }
                        
                            function after_select_inventario($data, $obj){
                            
                                return $data;
                            }
                        
                            function format_table_data_inventario($data, $obj){
                            
                                return $data;
                            }
                        
                            function format_table_col_inventario($data, $obj){
                            
                                return $data;
                            }
                        
