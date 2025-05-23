<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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

function insertar_mantencion_equipos($data, $obj){
    $emailBody = "Prueba de mensaje";
    $subject = "Correo Enviado para Mantención de Equipos";
    $to = "daniel.telematico@gmail.com";

    App\core\DB::PHPMail($to, "daniel.telematico@gmail.com", $subject, $emailBody);

    return $data;
}

function registrar_funcionarios($data, $obj){
    $usuario = $data["usuario"]["usuario"];
    $rut = $data["usuario"]["rut"];

    if (empty($usuario) && empty($rut)) {
        $error_msg = array("message" => "", "error" => "Debe ingresar un usuario o un RUT, pero no ambos.", "redirectionurl" => "");
        die(json_encode($error_msg));
    }

    if (!empty($usuario) && !empty($rut)) {
        $error_msg = array("message" => "", "error" => "Debe ingresar solo un usuario o un RUT, no ambos.", "redirectionurl" => "");
        die(json_encode($error_msg));
    }

    $avatar = $data["usuario"]["avatar"];
    if (empty($avatar)) {
        $image = ArtifyABSPATH . 'uploads/1710162578_user.png';
        $data["usuario"]["avatar"] =  basename($image);
    } else {
        $data["usuario"]["avatar"] = basename($avatar);
    }
    $data["usuario"]["password"] = password_hash($data["usuario"]["password"], PASSWORD_DEFAULT);
    return $data;
}

function despues_de_insertar_funcionarios($data, $obj){
    $userId = $data;

    $selectedMenus = [
        ["checked" => false, "menuId" => 4],
        ["checked" => true, "menuId" => 5],
        ["checked" => true, "menuId" => 6],
        ["checked" => true, "menuId" => 7],
        ["checked" => false, "menuId" => 10],
        ["checked" => false, "menuId" => 12],
        ["checked" => false, "menuId" => 19],
        ["checked" => false, "menuId" => 141],
        ["checked" => false, "menuId" => 284],
        ["checked" => true, "menuId" => 286],
        ["checked" => false, "menuId" => 287],
        ["checked" => true, "menuId" => 289],
    ];

    if (is_array($selectedMenus)) {
        $queryfy = $obj->getQueryfyObj();

        $menuMarcado = false;
        $menuDesmarcado = false;

        foreach ($selectedMenus as $menu) {
            $menuId = $menu["menuId"];
            $submenuIds = isset($menu["submenuIds"]) ? $menu["submenuIds"] : [];
            $checked = $menu["checked"];

            // Procesar el menú principal
            $existMenu = $queryfy->where('id_menu', $menuId)
                ->where('id_usuario', $userId)
                ->select('usuario_menu');

            switch ($checked) {
                case "true":
                    if (!$existMenu) {
                        $queryfy->insert('usuario_menu', array(
                            "id_usuario" => $userId,
                            "id_menu" => $menuId,
                            "visibilidad_menu" => "Mostrar"
                        ));
                        $menuMarcado = true;
                    } else {
                        $queryfy->where('id_usuario', $userId)
                            ->where('id_menu', $menuId)
                            ->update('usuario_menu', array("visibilidad_menu" => "Mostrar"));
                        $menuMarcado = true;
                    }
                    break;

                case "false":
                    $queryfy->where('id_usuario', $userId)
                        ->where('id_menu', $menuId)
                        ->update('usuario_menu', array("visibilidad_menu" => "Ocultar"));
                    $menuDesmarcado = true;
                    break;
            }

            // Procesar los submenús asociados al menú principal
            foreach ($submenuIds as $submenuId) {
                $id_submenu = $submenuId['id'];
                $checked = $submenuId["checked"];

                $existSubmenu = $queryfy->where('id_submenu', $id_submenu)
                    ->where('id_usuario', $userId)
                    ->select('usuario_submenu');

                switch ($checked) {
                    case "true":
                        if (!$existSubmenu) {
                            $queryfy->insert('usuario_submenu', array(
                                "id_usuario" => $userId,
                                "id_submenu" => $id_submenu,
                                "id_menu" => $menuId,
                                "visibilidad_submenu" => "Mostrar"
                            ));
                        } else {
                            $queryfy->where('id_usuario', $userId)
                                ->where('id_submenu', $id_submenu)
                                ->where('id_menu', $menuId)
                                ->update('usuario_submenu', array("visibilidad_submenu" => "Mostrar"));
                        }
                        break;

                    case "false":
                        $queryfy->where('id_usuario', $userId)
                            ->where('id_submenu', $id_submenu)
                            ->where('id_menu', $menuId)
                            ->update('usuario_submenu', array("visibilidad_submenu" => "Ocultar"));
                        break;
                }
            }
        }

        if ($menuMarcado) {
            $obj->setLangData("success", "Menús asignados correctamente");
        }

        if ($menuDesmarcado) {
            $obj->setLangData("success", "Menús Actualizados correctamente");
        }

        if (!$menuMarcado && !$menuDesmarcado) {
            $error_msg = array("message" => "", "error" => "Todos los menús ya fueron asignados previamente", "redirectionurl" => "");
            die(json_encode($error_msg));
        }

    } else {
        $error_msg = array("message" => "", "error" => "Debe seleccionar al menos 1 menú de la lista para continuar", "redirectionurl" => "");
        die(json_encode($error_msg));
    }

    return $data;
}


function buscador_tabla($data, $obj, $columnDB = array()) {
    $queryfy = $obj->getQueryfyObj();
    $tabla = $obj->getLangData("tabla");

    $columnNames = $queryfy->columnNames($tabla);
 
    $whereClause = "";
 
    if(isset($data["action"]) && $data["action"] == "search"){
        if (isset($data['search_col']) && isset($data['search_text'])) {
                $search_col = $data['search_col'];
                $search_text = $data['search_text'];
             
                // Sanitize inputs to prevent SQL injection
                $search_col = preg_replace('/[^a-zA-Z0-9_]/', '', $search_col);
                $search_text = htmlspecialchars($search_text, ENT_QUOTES, 'UTF-8');
             
            if ($search_text !== '') { 
                if ($search_col !== 'all') {
                    $whereClause = "WHERE $search_col LIKE '%$search_text%'";
                } else {
                    $whereConditions = [];
                    foreach ($columnNames as $columnName) {
                        $whereConditions[] = "$columnName LIKE '%$search_text%'";
                    }
                    $whereClause = "WHERE " . implode(" OR ", $whereConditions);
                }
            }
 
            $query = "SELECT id as ID, name as Name 
            FROM $tabla
            $whereClause";
 
            $obj->setQuery($query);
        }
    }
 
    return $data;
}

function format_sql_col_tabla($data, $obj, $columnDB = array()) {
    $queryfy = $obj->getQueryfyObj();
    $tabla = $obj->getLangData("tabla");
 
    $columnNames = $queryfy->columnNames($tabla);
 
    $template = array(
        'colname' => '',
        'tooltip' => '',
        'attr' => '',
        'sort' => '',
        'col' => '',
        'type' => ''
    );
 
    $default_cols = array();
    foreach ($columnDB as $column) {
        // Aplicar la plantilla y ajustar los valores específicos de la columna
        $details = $template;
        $details['colname'] = ucfirst(str_replace('_', ' ', $column));
        $details['col'] = $column;
 
        // Verificar si la columna está en la base de datos
        if (in_array($column, $columnNames)) {
            // Columna existente en la base de datos
            $default_cols[$column] = $details;
        } else {
            // Columna concatenada o que no está en la base de datos
            $default_cols[$column] = $details;
        }
    }

     // Convertir las claves de $data a minúsculas
    $data = array_change_key_case($data, CASE_LOWER);

    // Evitar duplicados y combinar datos de manera controlada
    foreach ($default_cols as $key => $value) {
        if (!array_key_exists($key, $data)) {
            $data[$key] = $value;
        }
    }
 
    return $data;
}

function eliminacion_masiva_tabla($data, $obj){
    $tabla = $obj->getLangData("tabla");
    $pk = $obj->getLangData("pk");
    $queryfy = $obj->getQueryfyObj();
 
    // Obtener los IDs seleccionados del array
    $selected_ids = $data["selected_ids"];
 
    // Asegurarse de que $selected_ids no esté vacío
    if (!empty($selected_ids)) {
        // Recorrer cada ID y eliminar el producto correspondiente
        foreach ($selected_ids as $id) {
            $queryfy->where($pk, $id);
            $queryfy->delete($tabla);
        }
    }
 
    return $data;
}

function eliminar_tabla($data, $obj){
    $tabla = $obj->langData["tabla"];
    $pk = $obj->langData["pk"];
    $queryfy = $obj->getQueryfyObj();
 
    $id = $data["id"];
 
    if (!empty($id)) {
        $queryfy->where($pk, $id);
        $queryfy->delete($tabla);
    }
 
    return $data;
}

function carga_masiva_nmedicos_insertar($data, $obj){
    $archivo = basename($data["carga_masiva_nmedicos"]["archivo"]);
    $extension = pathinfo($archivo, PATHINFO_EXTENSION);

    $queryfy = $obj->getQueryfyObj();
   
    $rutInvalidos = [];

    if (empty($archivo)) { 
        $error_msg = array("message" => "", "error" => "No se ha subido ningún Archivo", "redirectionurl" => "");
        die(json_encode($error_msg));
    } else {
        if ($extension != "xlsx") { /* comprobamos si la extensión del archivo es diferente de excel */
            //unlink(__DIR__ . "/uploads/".$archivo); /* eliminamos el archivo que se subió */
            $error_msg = array("message" => "", "error" => "El Archivo Subido no es un Archivo Excel Válido", "redirectionurl" => "");
            die(json_encode($error_msg));
        } else {

            $records = $queryfy->excelToArray("uploads/".$archivo); /* Acá capturamos el nombre del archivo excel a importar */

            $sql = array();
            foreach ($records as $Excelval) {

                $rut_completo = $Excelval['Rut'] . '-' . $Excelval['Dv'];

                if (!App\Controllers\HomeController::validaRut($rut_completo)) {
                    $rutInvalidos[] = $rut_completo;
                } else {

                    $existingMedico = $queryfy->DBQuery("SELECT * FROM nmedico WHERE rutmedico = :rut", ['rut' => $rut_completo]);

                    if (!$existingMedico) {
                        $sql = array(
                            'nmedico' => $Excelval['Nombre'],
                            'especialidad' => $Excelval['Especialidad'],
                            'rutmedico' => $rut_completo
                        );

                        $queryfy->insertBatch("nmedico", array($sql));
                    } else {
                        $error_msg = array("message" => "", "error" => "Lo Siguientes Médicos ingresados ya existen: ". implode(", ", $Excelval["Nombre"]), "redirectionurl" => "");
                        die(json_encode($error_msg));
                    }
                }
            }

            if (!empty($rutInvalidos)) {
                $error_msg = array("message" => "", "error" => "Los siguientes Rut inválidos no han sido cargados: " . implode(", ", $rutInvalidos), "redirectionurl" => "");
                die(json_encode($error_msg));
            }
            $data["carga_masiva_nmedicos"]["archivo"] = basename($data["carga_masiva_nmedicos"]["archivo"]);
        }
    }
    return $data;
}


function actualizar_criticosapa($data, $obj){
    $Idsolicitud = $data["criticosapa"]["Idsolicitud"];
    $fecharesultado = $data["criticosapa"]["fecharesultado"];
    $notificado = $data["criticosapa"]["notificado"];

    if($notificado == "si"){
        $queryfy = $obj->getQueryfyObj();
        $queryfy->insert("historico_caso", array(
            "tipo" => "6",
            "fecha_y_hora" => $fecharesultado,
            "Id_solicitud" => $Idsolicitud
        ));
    }

    $obj->setLangData("success", "Datos Actualizados con éxito");
    return $data;
}

function actualizar_notificar_paciente($data, $obj){
    $Idsolicitud = $data["criticosapa"]["Idsolicitud"];
    $fecha = $data["criticosapa"]["fecha"];
    $hora = $data["criticosapa"]["hora"];
    $nombre_funcionario = $data["criticosapa"]["nombre_funcionario"];
    $texto_libre = $data["criticosapa"]["texto_libre"];

    $queryfy = $obj->getQueryfyObj();
    $queryfy->insert("historico_caso", array(
        "tipo" => "5",
        "fecha_y_hora" => $fecha . ' ' . $hora,
        "Id_solicitud" => $Idsolicitud
    ));

    return $data;
}

function formatTableColCallBack($data, $obj){
    // Definir la nueva columna y su valor
    $newColumns = [
        'Imprimir' => [
            'colname' => 'Imprimir', // Nombre visible de la columna
            'tooltip' => '', // Tooltip, si es necesario
            'attr' => '', // Atributos adicionales, si es necesario
            'sort' => '', // Indicar si la columna es ordenable
            'col' => 'imprimir', // Nombre interno de la columna
            'type' => 'text', // Tipo de columna
        ],
        'resultados' => [
            'colname' => 'Resultados', // Nombre visible de la columna
            'tooltip' => '', // Tooltip, si es necesario
            'attr' => '', // Atributos adicionales, si es necesario
            'sort' => '', // Indicar si la columna es ordenable
            'col' => 'resultados', // Nombre interno de la columna
            'type' => 'text', // Tipo de columna
        ],
        'traza' => [
            'colname' => 'Traza', // Nombre visible de la columna
            'tooltip' => '', // Tooltip, si es necesario
            'attr' => '', // Atributos adicionales, si es necesario
            'sort' => '', // Indicar si la columna es ordenable
            'col' => 'traza', // Nombre interno de la columna
            'type' => 'text', // Tipo de columna
        ],
        'edicion' => [
            'colname' => 'Edición', // Nombre visible de la columna
            'tooltip' => '', // Tooltip, si es necesario
            'attr' => '', // Atributos adicionales, si es necesario
            'sort' => '', // Indicar si la columna es ordenable
            'col' => 'edicion', // Nombre interno de la columna
            'type' => 'text', // Tipo de columna
        ]
    ];

    // Agregar las nuevas columnas al array $data
    foreach ($newColumns as $key => $column) {
        $data[$key] = $column;
    }

    return $data;
}

function actualizar_solicitudesapa($data, $obj){
    $obj->setLangData("success", "Datos Actualizados con éxito");
    return $data;
}  

function formatTableDataCallBack($data, $obj){
        // Definir los nombres y valores de las nuevas columnas
        $newColumns = [
        'Imprimir' => function($row){
            return '<a class="btn btn-light btn-sm ver_solicitudes" href="javascript:;" title="Imprimir" data-id="'.$row['Idsolicitud'].'">
                        <i class="fa fa-print"></i>
                    </a>
                    <a class="btn btn-light btn-sm ver_etiquetas" href="javascript:;" title="Etiqueta" data-id="'.$row['Idsolicitud'].'">
                        <i class="fa fa-barcode"></i>
                    </a>';
        },        
        'resultados' => function($row) {
            return '<a class="btn btn-light btn-sm ver_resultado" href="javascript:;" title="Resultado" data-id="'.$row['Idsolicitud'].'">
                        <i class="fa fa-upload"></i>
                    </a>
                    <a class="btn btn-light btn-sm ver_pdf" href="javascript:;" title="PDF" data-id="'.$row['Idsolicitud'].'">
                        <i class="fa fa-file-pdf-o"></i>
                    </a>';
        },
        'traza' => function($row) {
            return '<a class="btn btn-light btn-sm ver_traza" href="javascript:;" title="Traza" data-id="'.$row['Idsolicitud'].'">
                        <i class="fa fa-info-circle"></i>
                    </a>';
        },
        'edicion' => function($row) {
            return '<a class="artify-actions btn btn-warning btn-sm artify-button artify-button-edit" href="javascript:;" title="Editar" data-id="'.$row['Idsolicitud'].'" data-action="edit">
                        <i class="fa fa-pencil-square-o"></i>
                    </a>
                    <a class="btn btn-danger btn-sm eliminar_solicitudes" href="javascript:;" title="Eliminar" data-id="'.$row['Idsolicitud'].'">
                        <i class="fa fa-trash"></i>
                    </a>';
        }
    ];

    // Iterar sobre cada fila de datos y agregar las nuevas columnas
    foreach ($data as &$row) {
        foreach ($newColumns as $colName => $value) {
            if (is_callable($value)) {
                // Si el valor es una función, llámala con la fila actual
                $row[$colName] = $value($row);
            } else {
                // Si el valor no es una función, asígnalo directamente
                $row[$colName] = $value;
            }
        }
    }

    return $data;
}

function actualizar_resultados($data, $obj){
    $Idsolicitud = $data["solicitudesapa"]["Idsolicitud"];
    $estado = $data["solicitudesapa"]["estado"];
    $fecharesultado = $data["solicitudesapa"]["fecharesultado"];
    $nmedico = $data["solicitudesapa"]["nmedico"];
    $critico = $data["solicitudesapa"]["critico"];
    $rut =  $data["solicitudesapa"]["rut"];
    $nombres = $data["solicitudesapa"]["nombres"];
    $apaterno = $data["solicitudesapa"]["apaterno"];
    $amaterno = $data["solicitudesapa"]["amaterno"];
    $resultado = basename($data["solicitudesapa"]["resultado"]);

    $explode = explode('.', $resultado);
    $extension = array_pop($explode);

    if ($extension != "pdf") {
        $error_msg = array("message" => "", "error" => "El Archivo Subido no es un Archivo PDF Válido", "redirectionurl" => "");
        die(json_encode($error_msg));
    }

    $fecharesultadoDateTime = DateTime::createFromFormat('Y-m-d\TH:i', $fecharesultado);
    if ($fecharesultadoDateTime) {
        $fecharesultadoMysql = $fecharesultadoDateTime->format('Y-m-d H:i:s');
    } else {
        // Manejar el error si la fecha no tiene el formato esperado
        $fecharesultadoMysql = null; // O puedes elegir otro valor por defecto
    }

    if ($critico == 'si') {
        $queryfy = $obj->getQueryfyObj();
       
        $queryfy->insert("criticosapa", array(
            "Id_solicitud" => $Idsolicitud, 
            "rut" => $rut,
            "nombres" => $nombres,
            "apaterno" => $apaterno,
            "amaterno" => $amaterno, 
            "fecharesultado" => $fecharesultado,
            "nmedico" => $nmedico
        ));    
    }

    $queryfy = $obj->getQueryfyObj();
    $queryfy->where("Idsolicitud", $Idsolicitud);
    $solicitudes = $queryfy->select("solicitudesapa");

    $queryfy->insert("historico_caso", array(
        "tipo" => $solicitudes[0]["estado"],
        "fecha_y_hora" => $fecharesultadoMysql,
        "Id_solicitud" => $Idsolicitud
    ));

    $data["solicitudesapa"]["resultado"] = basename($data["solicitudesapa"]["resultado"]);
    $data["solicitudesapa"]["fecharesultado"] = $fecharesultadoMysql;
    $obj->setLangData("success", "Resultados Actualizados con éxito");
    return $data;
}


function search_table($data, $obj) {
    if (isset($data["action"]) && $data["action"] == "search") {
        if (isset($data['search_col']) && isset($data['search_text'])) {
            $search_col = $data['search_col'];
            $search_text = $data['search_text'];

            // Limpiar condiciones previas
            $obj->clearWhereConditions();

            // Si se busca por 'all', aplicar condiciones a todas las columnas relevantes
            if ($search_col == 'all') {
                $obj->where("Idsolicitud", "%$search_text%", "LIKE", "OR")
                    ->where("fechatoma", "%$search_text%", "LIKE", "OR")
                    ->where("rut", "%$search_text%", "LIKE", "OR")
                    ->where("CONCAT(nombres, ' ', apaterno, ' ', amaterno)", "%$search_text%", "LIKE", "OR")
                    ->where("tipomuestra", "%$search_text%", "LIKE", "OR")
                    ->where("servicio", "%$search_text%", "LIKE", "OR")
                    ->where("dgclinico", "%$search_text%", "LIKE", "OR")
                    ->where("organo", "%$search_text%", "LIKE", "OR")
                    ->where("nmedico", "%$search_text%", "LIKE", "OR")
                    ->where("centroderivacion", "%$search_text%", "LIKE", "OR")
                    ->where("estado", "%$search_text%", "LIKE");
                    
            } else {
                // Aplicar condición en la columna específica
                if ($search_col == 'nombres') {
                    $obj->where("CONCAT(nombres, ' ', apaterno, ' ', amaterno)", "%$search_text%", "LIKE");
                } else {
                    $obj->where($search_col, "%$search_text%", "LIKE");
                }
            }
        }
    }
    return $data;
}


function beforeTableDataCallBackCriticos($data, $obj){
    if(isset($data['search_col']) && $data['search_col'] == 'all'){
        $obj->setSearchOperator("LIKE");

        if (isset($data['search_text'])) {
            $date = DateTime::createFromFormat('d-m-Y H:i:s', $data['search_text']);

            // Si se ha logrado convertir a una fecha válida
            if ($date) {
                $data['search_text'] = $date->format('Y-m-d H:i:s');
            }
        }
    }

    return $data;
}

function despues_de_insertar_solicitudesapa($data, $obj){
    $id = $data;   
    $queryfy = $obj->getQueryfyObj();

    $queryfy->where("Idsolicitud", $id);
    $solicitudes = $queryfy->select("solicitudesapa");

    $queryfy->insert("historico_caso", array(
        "tipo" => $solicitudes[0]["estado"],
        "fecha_y_hora" => $solicitudes[0]["fecharegistro"],
        "Id_solicitud" => $id
    ));

    return $data;
}

function agregar_detalle_muestra($data, $obj){
    $run = $data["solicitudesapa"]["rut"];
    if (!App\Controllers\HomeController::validaRut($run)) {
        $error_msg = array("message" => "", "error" => "El Run Ingresado no es Válido", "redirectionurl" => "");
        die(json_encode($error_msg));
    }

    $obj->setLangData("success", "Datos Guardados con éxito");
    
    return $data;
}

function seleccionar_solicitudesapa($data, $obj){
    $id = isset($_POST["id"]) ? explode(",", $_POST["id"]) : array();
    $estado = $data["solicitudesapa"]["estado"];
    $fechaderivacion = $data["solicitudesapa"]["fechaderivacion"];

    if(empty($_POST["id"])){
        $error_msg = array("message" => "", "error" => "El campo Ingreso de Ids es Requerido", "redirectionurl" => "");
        die(json_encode($error_msg));
    }

    if(empty($estado)){
        $error_msg = array("message" => "", "error" => "El campo Estado es Requerido", "redirectionurl" => "");
        die(json_encode($error_msg));
    }

    if(empty($fechaderivacion)){
        $error_msg = array("message" => "", "error" => "El campo Fecharecepcion es Requerido", "redirectionurl" => "");
        die(json_encode($error_msg));
    }

    $queryfy = $obj->getQueryfyObj();
    foreach ($id as $Idsolicitud) {
        $queryfy->where("Idsolicitud", $Idsolicitud);
        $queryfy->update("solicitudesapa", array(
            "estado" => $estado, 
            "fechaderivacion" => $fechaderivacion
        ));

        $queryfy->insert("historico_caso", array(
            "tipo" => $estado,
            "fecha_y_hora" => $fechaderivacion,
            "Id_solicitud" => $Idsolicitud
        ));
    }

    $obj->setLangData("success", "Registros actualizados correctamente");

    $newdata = array();
    $newdata["solicitudesapa"]["estado"] = $estado;
    $newdata["solicitudesapa"]["fechaderivacion"] = $fechaderivacion;

    return $newdata;
}

function seleccionar_solicitudesapa_derivacion($data, $obj){
    $id = isset($_POST["id"]) ? explode(",", $_POST["id"]) : array();
    $estado = $data["solicitudesapa"]["estado"];
    $fechaderivacion = $data["solicitudesapa"]["fechaderivacion"];
    $centroderivacion = $data["solicitudesapa"]["centroderivacion"];

    if(empty($_POST["id"])) {
        $error_msg = array("message" => "", "error" => "El campo Ingreso de Ids es Requerido", "redirectionurl" => "");
        die(json_encode($error_msg));
    }

    if(empty($estado)){
        $error_msg = array("message" => "", "error" => "El campo Estado es Requerido", "redirectionurl" => "");
        die(json_encode($error_msg));
    }

    if(empty($fechaderivacion)){
        $error_msg = array("message" => "", "error" => "El campo fechaderivacion es Requerido", "redirectionurl" => "");
        die(json_encode($error_msg));
    }

    if(empty($centroderivacion)){
        $error_msg = array("message" => "", "error" => "El campo centroderivacion es Requerido", "redirectionurl" => "");
        die(json_encode($error_msg));
    }

    $queryfy = $obj->getQueryfyObj();
    foreach ($id as $Idsolicitud) {
        $queryfy->where("Idsolicitud", $Idsolicitud);
        $queryfy->update("solicitudesapa", array(
            "estado" => $estado, 
            "fechaderivacion" => $fechaderivacion, 
            "centroderivacion" => $centroderivacion
        ));

        $queryfy->insert("historico_caso", array(
            "tipo" => $estado,
            "fecha_y_hora" => $fechaderivacion,
            "Id_solicitud" => $Idsolicitud
        ));
    }

    $obj->setLangData("success", "Registros actualizados correctamente");

    $newdata = array();
    $newdata["solicitudesapa"]["estado"] = $estado;
    $newdata["solicitudesapa"]["fechaderivacion"] = $fechaderivacion;

    return $newdata;
}

function actualizar_configuracion($data, $obj){
    $data["configuracion_general"]["logo_login"] = basename($data["configuracion_general"]["logo_login"]);
    $data["configuracion_general"]["imagen_de_fondo_login"] = basename($data["configuracion_general"]["imagen_de_fondo_login"]);
    $data["configuracion_general"]["imagen_de_carga"] = basename($data["configuracion_general"]["imagen_de_carga"]);
    return $data;
}

function agregar_menu($data, $obj){
    $id_menu = $data;
    $id_usuario_session = $_SESSION["usuario"][0]["id"];

    $queryfy = $obj->getQueryfyObj();
    $queryfy->insert("usuario_menu", array("id_menu" => $id_menu, "id_usuario" => $id_usuario_session, "visibilidad_menu" => "Mostrar"));

    return $data;
}

function despues_insertar_submenu($data, $obj){
    $id_submenu = $data;
    $id_usuario_session = $_SESSION["usuario"][0]["id"];

    $queryfy = $obj->getQueryfyObj();
    $queryfy->where("id_submenu", $id_submenu);
    $id_menu = $queryfy->select("submenu");
    $queryfy->insert("usuario_submenu", array("id_menu" => $id_menu[0]["id_menu"], "id_submenu" => $id_submenu, "id_usuario" => $id_usuario_session, "visibilidad_submenu" => "Mostrar"));

    return $data;
}

function eliminar_menu($data, $obj) {
    $id_menu = $data["id"];
    $id_usuario_session = $_SESSION["usuario"][0]["id"];
    
    $queryfy = $obj->getQueryfyObj();

    // Eliminar de usuario_menu
    $queryfy->where("id_menu", $id_menu);
    $queryfy->where("id_usuario", $id_usuario_session);
    $queryfy->delete("usuario_menu");

    // Buscar el id_submenu relacionado al id_menu
    $queryfy->where("id_menu", $id_menu);
    $id_menu_db = $queryfy->select("submenu");

    // Verificar si se encontró el id_submenu
    if (!empty($id_menu_db)) {
        // Eliminar el submenu relacionado
        $queryfy->where("id_submenu", $id_menu_db[0]["id_submenu"]);
        $queryfy->delete("submenu");

        // Eliminar de usuario_submenu relacionado
        $queryfy->where("id_menu", $id_menu);
        $queryfy->where("id_usuario", $id_usuario_session);
        $queryfy->delete("usuario_submenu");
    } else {
        // Si no hay submenus, actualizar el campo "submenu" en la tabla menu
        $queryfy->where("id_menu", $id_menu);
        $queryfy->update("menu", array("submenu" => "No"));
    }

    return $data;
}

function eliminar_submenu($data, $obj){
    $id_submenu = $data["id"];
    $id_usuario_session = $_SESSION["usuario"][0]["id"];

    $queryfy = $obj->getQueryfyObj();

    $queryfy->where("id_submenu", $id_submenu);
    $id_menu = $queryfy->select("submenu");

    $result = $queryfy->DBQuery("SELECT COUNT(*) AS total FROM submenu WHERE id_menu = :id_menu", [":id_menu" => $id_menu[0]["id_menu"]]);

    $num_submenus = $result[0]["total"];

    if ($num_submenus == 0) {
        $queryfy->where("id_menu", $id_menu[0]["id_menu"]);
        $queryfy->update("menu", array("submenu" => "No"));
    }

    $queryfy->where("id_submenu", $id_submenu);
    $queryfy->where("id_usuario", $id_usuario_session);
    $queryfy->delete("usuario_submenu");

    return $data;
}

function carga_masiva_prestaciones_insertar($data, $obj){
    $archivo = basename($data["carga_masiva_prestaciones"]["archivo"]);

    $explode = explode('.', $archivo);
    $extension = array_pop($explode);

    $queryfy = $obj->getQueryfyObj();
   
    if (empty($archivo)) { 
        $error_msg = array("message" => "", "error" => "No se ha subido ningún Archivo", "redirectionurl" => "");
        die(json_encode($error_msg));
    } else {
        if ($extension != "xlsx") { /* comprobamos si la extensión del archivo es diferente de excel */
            unlink(__DIR__ . "/uploads/".$archivo); /* eliminamos el archivo que se subió */
            $error_msg = array("message" => "", "error" => "El Archivo Subido no es un Archivo Excel Válido", "redirectionurl" => "");
            die(json_encode($error_msg));

        } else {

            $records = $queryfy->excelToArray("uploads/".$archivo); /* Acá capturamos el nombre del archivo excel a importar */

            $sql = array();
            foreach ($records as $Excelval) {
                $sql['tipo_solicitud'] = $Excelval['TIPO SOLICITUD'];
                $sql['especialidad'] = $Excelval['ESPECIALIDAD'];
                $sql['tipo_de_examen'] = $Excelval['TIPO DE EXAMEN'];
                $sql['examen'] = $Excelval['EXAMEN'];
                $sql['codigo_fonasa'] = $Excelval['CODIGO FONASA'];
                $sql['glosa'] = $Excelval['GLOSA'];

                $queryfy->insertBatch("prestaciones", array($sql));
            }
            $data["carga_masiva_prestaciones"]["archivo"] = basename($data["carga_masiva_prestaciones"]["archivo"]);
        }
    }
    return $data;
}

function insertar_detalle_solicitud($data, $obj){
    return $data;
}

function insertar_procedimientos($data, $obj){
    $rut = $data["procedimiento"]["rut"];
    $fecha_solicitud = $data["procedimiento"]["fecha_solicitud"];
    $especialidad = $data["procedimiento"]["procedimiento"];
    $procedimiento_2 = $data["procedimiento"]["procedimiento_2"];
    $servicio = $data["procedimiento"]["servicio"];
    $fecha_registro = $data["procedimiento"]["fecha_registro"];
    $nombres = $data["procedimiento"]["nombres"];
    $apellido_paterno = $data["procedimiento"]["apellido_paterno"];
    $apellido_materno = $data["procedimiento"]["apellido_materno"];
    $operacion = $data["procedimiento"]["operacion"];
    $profesional_solicitante = $data["procedimiento"]["profesional_solicitante"];
    $numero_contacto = $data["procedimiento"]["numero_contacto"];
    $numero_contacto_2 = $data["procedimiento"]["numero_contacto_2"];
    $prioridad = $data["procedimiento"]["prioridad"];

    if(empty($rut) && empty($especialidad) && empty($procedimiento_2) && empty($servicio) && empty($nombres) && empty($apellido_paterno) && empty($apellido_materno) && empty($operacion) && empty($profesional_solicitante) && empty($numero_contacto) && empty($numero_contacto_2) && empty($prioridad)){
        $error_msg = array("message" => "", "error" => "Todos los campos son obligatorios", "redirectionurl" => "");
        die(json_encode($error_msg));
    }

    $newdata = array();
    $newdata["procedimiento"]["rut"] = $rut;
    $newdata["procedimiento"]["fecha_solicitud"] = $fecha_solicitud;
    $newdata["procedimiento"]["procedimiento"] = $procedimiento;
    $newdata["procedimiento"]["procedimiento_2"] = $procedimiento_2;
    $newdata["procedimiento"]["servicio"] = $servicio;
    $newdata["procedimiento"]["fecha_registro"] = $fecha_registro;
    $newdata["procedimiento"]["nombres"] = $nombres;
    $newdata["procedimiento"]["apellido_paterno"] = $apellido_paterno;
    $newdata["procedimiento"]["apellido_materno"] = $apellido_materno;
    $newdata["procedimiento"]["operacion"] = $operacion;
    $newdata["procedimiento"]["profesional_solicitante"] = $profesional_solicitante;
    $newdata["procedimiento"]["numero_contacto"] = $numero_contacto;
    $newdata["procedimiento"]["numero_contacto_2"] = $numero_contacto_2;
    $newdata["procedimiento"]["prioridad"] = $prioridad;

    return $newdata;
}

function delete_file_data($data, $obj)
{
    $id = $data['id'];
    $queryfy = $obj->getQueryfyObj();
    $queryfy->fetchType = "OBJ";
    $queryfy->where("id", $id);
    $result = $queryfy->select("backup");

    $file_sql = $result[0]->archivo;

    $file_crop = "uploads/".$file_sql;

    if (file_exists($file_crop)) {
        unlink($file_crop);
        echo "<script>
        Swal.fire({
            title: 'Genial!',
            text: 'Respaldo Eliminado con éxito',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });
        </script>";
    }
    return $data;
}

function eliminar_detalle_solicitud($data, $obj){
    /*$id = $data["id"];
    
    $queryfy = $obj->getQueryfyObj();
    $queryfy->where("id_detalle_de_solicitud", $id);
    $result = $queryfy->select("detalle_de_solicitud");
    
    $id_datos_paciente = $result[0]["id_datos_paciente"];
    $queryfy->where("id_datos_paciente", $id_datos_paciente);
    $queryfy->delete("diagnostico_antecedentes_paciente");*/
    return $data;
}

function before_sql_data_estat($data, $obj){
    //print_r($data);
    return $data;
}

/*function editar_procedimientos($data, $obj){
    $id_datos_paciente = $data['datos_paciente']['id_datos_paciente'];
    $estado = $data["detalle_de_solicitud"]["estado"];
    $fecha = $data["detalle_de_solicitud"]["fecha"];
    $fecha_solicitud = $data["detalle_de_solicitud"]["fecha_solicitud"];
    $fundamento = $data['diagnostico_antecedentes_paciente']['fundamento'];
    $adjuntar = $data['diagnostico_antecedentes_paciente']['adjuntar'];
    $id_detalle_de_solicitud = $data["detalle_de_solicitud"]["id_detalle_de_solicitud"];
    $id_diagnostico_antecedentes_paciente = $data["diagnostico_antecedentes_paciente"]["id_diagnostico_antecedentes_paciente"];
 
    $queryfy = $obj->getQueryfyObj();
    $queryfy->where("id_detalle_de_solicitud", $id_detalle_de_solicitud, "=");
    $data_detalle = $queryfy->select("detalle_de_solicitud");
   
    $queryfy->where("id_diagnostico_antecedentes_paciente", $id_diagnostico_antecedentes_paciente, "=");
    $data_diagnostico = $queryfy->select("diagnostico_antecedentes_paciente");
    
    if($data_detalle && $data_diagnostico){
        $queryfy->where("id_detalle_de_solicitud", $id_detalle_de_solicitud, "=", "AND");
        $queryfy->update("detalle_de_solicitud", array("fecha" => $fecha, "estado" => $estado));

        $queryfy->where("id_diagnostico_antecedentes_paciente", $id_diagnostico_antecedentes_paciente);
        $queryfy->update("diagnostico_antecedentes_paciente", array("fundamento" => $fundamento, "adjuntar" => basename($adjuntar)));

        $success = array("message" => "Operación realizada con éxito", "error" => [], "redirectionurl" => "");
        die(json_encode($success));
    }

    $newdata = array();
    $newdata['datos_paciente']['id_datos_paciente'] = $id_datos_paciente;
    $newdata['diagnostico_antecedentes_paciente']['estado'] = $estado;
    $newdata['diagnostico_antecedentes_paciente']['diagnostico'] = $data['diagnostico_antecedentes_paciente']['diagnostico'];

    return $newdata;
}*/

function editar_procedimientos($data, $obj){
    $id_datos_paciente = $data["datos_paciente"]["id_datos_paciente"];
    $estado = $data["detalle_de_solicitud"]["estado"];
    $fecha = $data["detalle_de_solicitud"]["fecha"];
    $fecha_solicitud = $data["detalle_de_solicitud"]["fecha_solicitud"];
    $adjuntar = $data["diagnostico_antecedentes_paciente"]["adjuntar"];
    $diagnostico = $data["diagnostico_antecedentes_paciente"]["diagnostico"];
    $fundamento = $data["diagnostico_antecedentes_paciente"]["fundamento"];

    $queryfy = $obj->getQueryfyObj();
    $queryfy->columns = array("fecha", "datos_paciente.id_datos_paciente", "fecha_solicitud", "diagnostico", "fundamento", "adjuntar", "estado");
    $queryfy->joinTables("detalle_de_solicitud", "detalle_de_solicitud.id_datos_paciente = datos_paciente.id_datos_paciente", "INNER JOIN");
    $queryfy->joinTables("diagnostico_antecedentes_paciente", "diagnostico_antecedentes_paciente.id_datos_paciente = datos_paciente.id_datos_paciente", "INNER JOIN");

    // Filtrar por ID y Fecha
    $queryfy->where("datos_paciente.id_datos_paciente", $id_datos_paciente);
    $queryfy->where("detalle_de_solicitud.fecha_solicitud", $fecha_solicitud);

    // Condiciones para verificar si los valores son diferentes
    $queryfy->where("detalle_de_solicitud.estado", $estado, "=");
    $queryfy->where("detalle_de_solicitud.fecha", $fecha, "=");
    $queryfy->where("diagnostico_antecedentes_paciente.diagnostico", $diagnostico, "=");
    $queryfy->where("diagnostico_antecedentes_paciente.fundamento", $fundamento, "=");
    $queryfy->where("diagnostico_antecedentes_paciente.adjuntar", $adjuntar, "=");

     // Seleccionar para verificar si existen registros con condiciones diferentes
    $result = $queryfy->select("datos_paciente");
    
    if ($result) {
        $error_msg = array("message" => "", "error" => "Modifique los campos para actualizar", "redirectionurl" => "");
        die(json_encode($error_msg));
    }
    
    $queryfy->where("id_datos_paciente", $id_datos_paciente);
    $queryfy->where("detalle_de_solicitud.fecha_solicitud", $fecha_solicitud);
    $queryfy->update("detalle_de_solicitud", array("estado" => $estado, "fecha" => $fecha));
    $queryfy->update("diagnostico_antecedentes_paciente", array("adjuntar" => $adjuntar, "diagnostico" => $diagnostico, "fundamento" => $fundamento));
    
    return $data;
}


function editar_egresar_solicitud($data, $obj) {
    $id_datos_paciente = $data['datos_paciente']['id_datos_paciente'];
    $fecha_egreso = $data['diagnostico_antecedentes_paciente']['fecha_egreso'];
    $motivo_egreso = $data['diagnostico_antecedentes_paciente']['motivo_egreso'];
    $observacion = $_POST['observacion'];

    $queryfy = $obj->getQueryfyObj();
    $queryfy->where("observacion", $observacion, "!=", "AND");
    $queryfy->where("id_datos_paciente", $id_datos_paciente, "=");
    $data_observacion = $queryfy->select("detalle_de_solicitud");

    if($data_observacion){
        $queryfy->where("id_datos_paciente", $id_datos_paciente);
        $queryfy->update("detalle_de_solicitud", array("observacion" => $observacion));

        $success = array("message" => "Operación realizada con éxito", "error" => [], "redirectionurl" => "");
        die(json_encode($success));
    }

    $newdata = array();
    $newdata['datos_paciente']['id_datos_paciente'] = $id_datos_paciente;
    $newdata['diagnostico_antecedentes_paciente']['fecha_egreso'] = $fecha_egreso;
    $newdata['diagnostico_antecedentes_paciente']['motivo_egreso'] = $motivo_egreso;

    return $newdata;
}


function formatTable_datos_paciente($data, $obj){
    if($data){
        for ($i = 0; $i < count($data); $i++) {
            if($data[$i]["fecha_y_hora_ingreso"] != "0000-00-00 00:00:00"){
                $data[$i]["fecha_y_hora_ingreso"] = "<div class='badge badge-success'>" . $data[$i]["fecha_y_hora_ingreso"] . "</div>";
            } else {
                $data[$i]["fecha_y_hora_ingreso"] = "<div class='badge badge-success'>Sin Fecha</div>";
            }

            if($data[$i]["edad"] == "0"){
                $data[$i]["edad"] = "<div class='badge badge-danger'>Sin Edad</div>";
            } else {
                $data[$i]["edad"] = $data[$i]["edad"];
            }
        }
    }
    return $data;
}

function editar_lista_examenes_notas($data, $obj){
    $id_datos_paciente = $data["datos_paciente"]["id_datos_paciente"];
    $fecha_solicitud = $data["detalle_de_solicitud"]["fecha_solicitud"];
    $observacion = $data["detalle_de_solicitud"]["observacion"];

    $queryfy = $obj->getQueryfyObj();
    $queryfy->columns = array("datos_paciente.id_datos_paciente", "fecha_solicitud", "observacion");
    $queryfy->joinTables("detalle_de_solicitud", "detalle_de_solicitud.id_datos_paciente = datos_paciente.id_datos_paciente", "INNER JOIN");

    $queryfy->where("datos_paciente.id_datos_paciente", $id_datos_paciente, "=", "AND");
    $queryfy->where("detalle_de_solicitud.fecha_solicitud", $fecha_solicitud);
    
    $queryfy->where("observacion", $observacion, "=");
    $result = $queryfy->select("datos_paciente");

    if ($result) {
        $error_msg = array("message" => "", "error" => "Modifique los campos para actualizar", "redirectionurl" => "");
        die(json_encode($error_msg));
    }
    
    $queryfy->where("id_datos_paciente", $id_datos_paciente);
    $queryfy->where("detalle_de_solicitud.fecha_solicitud", $fecha_solicitud);
    $queryfy->update("detalle_de_solicitud", array("observacion" => $observacion));

    return $data;
}


function insertar_generador_pdf($data, $obj){
    $data["generador_pdf"]["logo"] = basename($data["generador_pdf"]["logo"]);
    return $data;
}


function formatTable_buscar_examenes($data, $obj){
    if($data){
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]["nombres"] = ucwords($data[$i]["nombres"]) . " " .  ucwords($data[$i]["apellido_paterno"]) . " " . ucwords($data[$i]["apellido_materno"]);

            if($data[$i]["fecha_y_hora_ingreso"] == "0000-00-00 00:00:00"){
                $data[$i]["fecha_y_hora_ingreso"] = "<div class='badge badge-danger'>Sin Fecha</div>";
            } else {
                $data[$i]["fecha_y_hora_ingreso"] = date('d/m/Y H:i:s', strtotime($data[$i]["fecha_y_hora_ingreso"]));
            }

            if($data[$i]["fecha"] != null){
                $data[$i]["fecha"] = date('d/m/Y', strtotime($data[$i]["fecha"]));
            } else {
                $data[$i]["fecha"] = "<div class='badge badge-danger'>Sin Fecha</div>";
            }

            $data[$i]["profesional"] = ucwords($data[$i]["profesional"]);
        }
    }
    return $data;
}

function insertar_crear_tablas($data, $obj){
    $nombre_tabla = $data["crear_tablas"]["nombre_tabla"];
    $query_tabla = $data["crear_tablas"]["query_tabla"];

    $queryfy = $obj->getQueryfyObj();
    $resultado = $queryfy->create_table($nombre_tabla, $query_tabla);
    
    if ($resultado) {
        $obj->setLangData("success", "Tabla creada exitosamente");
    } else {
        $obj->setLangData("no_data", "Error al crear la tabla");
    }
    
    return $data;
}

function editar_crear_tablas($data, $obj){
    $nombre_tabla = $data["crear_tablas"]["nombre_tabla"];
    $modificar_tabla = $data["crear_tablas"]["modificar_tabla"];

    $queryfy = $obj->getQueryfyObj();
    $resultado = $queryfy->alter_table($nombre_tabla, $modificar_tabla);
    
    if ($resultado) {
        $obj->setLangData("success", "La tabla se ha modificado correctamente");
    } else {
        $obj->setLangData("no_data", "Hubo un error al modificar la tabla");
    }
    return $data;
}

function eliminar_crear_tablas($data, $obj){
    $id = $data["id"];
    $queryfy = $obj->getQueryfyObj();
    $queryfy->where("id_crear_tablas", $id);
    $tabla = $queryfy->select("crear_tablas");

    $queryfy->dropTable($tabla[0]["nombre_tabla"]);
    return $data;
}

function actualizar_configuracion_api($data, $obj){
    $generar_jwt_token = isset($data["configuraciones_api"]["generar_jwt_token"]) ? $data["configuraciones_api"]["generar_jwt_token"] : null;
    $autenticar_jwt_token = isset($data["configuraciones_api"]["autenticar_jwt_token"]) ? $data["configuraciones_api"]["autenticar_jwt_token"] : null;
    $tiempo_caducidad_token = isset($data["configuraciones_api"]["tiempo_caducidad_token"]) ? $data["configuraciones_api"]["tiempo_caducidad_token"] : null;
   
    $newdata = array();
    $newdata["configuraciones_api"]["generar_jwt_token"] = $generar_jwt_token;
    $newdata["configuraciones_api"]["autenticar_jwt_token"] = $autenticar_jwt_token;
    $newdata["configuraciones_api"]["tiempo_caducidad_token"] = $tiempo_caducidad_token;
    return $newdata;
}

function insertar_modulos($data, $obj, $id_sesion_usuario = null) {
    $tabla = $data["modulos"]["tabla"];
    $id_tabla = $data["modulos"]["id_tabla"];
    $crud_type = $data["modulos"]["crud_type"];
    $query_db = $data["modulos"]["query"] ?? null;
    $controller_name = $data["modulos"]["controller_name"];
    $name_view = $data["modulos"]["name_view"];
    $add_menu = $data["modulos"]["add_menu"];
    $template_fields = $data["modulos"]["template_fields"] ?? 'No';
    $active_filter = $data["modulos"]["active_filter"] ?? 'No';
    $mostrar_campos_filtro = $data["modulos"]["mostrar_campos_filtro"] ?? 'No';
    $clone_row = $data["modulos"]["clone_row"] ?? null;
    $active_popup = $data["modulos"]["active_popup"] ?? null;
    $active_search = $data["modulos"]["active_search"] ?? null;
    $activate_deleteMultipleBtn = $data["modulos"]["activate_deleteMultipleBtn"] ?? null;
    $button_add = $data["modulos"]["button_add"] ?? null;
    $actions_buttons_grid = $data["modulos"]["actions_buttons_grid"] ?? null;
    $activate_nested_table = $data["modulos"]["activate_nested_table"];
    $buttons_actions = $data["modulos"]["buttons_actions"] ?? null;
    $refrescar_grilla = $data["modulos"]["refrescar_grilla"] ?? 'No';
    $encryption = $data["modulos"]["encryption"];
    $mostrar_campos_busqueda = $data["modulos"]["mostrar_campos_busqueda"] ?? null;
    $mostrar_columnas_grilla = $data["modulos"]["mostrar_columnas_grilla"] ?? null;
    $mostrar_campos_formulario = $data["modulos"]["mostrar_campos_formulario"];
    $activar_recaptcha = $data["modulos"]["activar_recaptcha"];
    $sitekey_recaptcha = $data["modulos"]["sitekey_recaptcha"] ?? null;
    $sitesecret_repatcha = $data["modulos"]["sitesecret_repatcha"] ?? null;
    $function_filter_and_search = $data["modulos"]["function_filter_and_search"] ?? 'No';
    $activar_union_interna = $data["modulos"]["activar_union_interna"];
    $tabla_principal_union = $data["modulos"]["tabla_principal_union"] ?? null;
    $tabla_secundaria_union = $data["modulos"]["tabla_secundaria_union"] ?? null;
    $campos_relacion_union_tabla_principal = $data["modulos"]["campos_relacion_union_tabla_principal"] ?? null;
    $campos_relacion_union_tabla_secundaria = $data["modulos"]["campos_relacion_union_tabla_secundaria"] ?? null;
    $activar_union_izquierda = $data["modulos"]["activar_union_izquierda"];
    $tabla_principal_union_izquierda = $data["modulos"]["tabla_principal_union_izquierda"] ?? null;
    $tabla_secundaria_union_izquierda = $data["modulos"]["tabla_secundaria_union_izquierda"] ?? null;
    $campos_relacion_union_tabla_principal_izquierda = $data["modulos"]["campos_relacion_union_tabla_principal_izquierda"] ?? null;
    $campos_relacion_union_tabla_secundaria_izquierda = $data["modulos"]["campos_relacion_union_tabla_secundaria_izquierda"] ?? null;
    $mostrar_campos_formulario_editar = $data["modulos"]["mostrar_campos_formulario_editar"] ?? null;
    $posicion_botones_accion_grilla = $data["modulos"]["posicion_botones_accion_grilla"] ?? 'Derecha';
    $mostrar_columna_acciones_grilla = $data["modulos"]["mostrar_columna_acciones_grilla"] ?? 'No';
    $campos_requeridos = $data["modulos"]["campos_requeridos"];
    $mostrar_paginacion = $data["modulos"]["mostrar_paginacion"] ?? 'No';
    $activar_numeracion_columnas = $data["modulos"]["activar_numeracion_columnas"] ?? 'No';
    $activar_registros_por_pagina = $data["modulos"]["activar_registros_por_pagina"] ?? 'No';
    $cantidad_de_registros_por_pagina = $data["modulos"]["cantidad_de_registros_por_pagina"] ?? null;
    $activar_edicion_en_linea = $data["modulos"]["activar_edicion_en_linea"] ?? 'No';
    $nombre_modulo = $data["modulos"]["nombre_modulo"];
    $ordenar_grilla_por = $data["modulos"]["ordenar_grilla_por"] ?? null;
    $tipo_orden = $data["modulos"]["tipo_orden"] ?? null;
    $posicionarse_en_la_pagina = $data["modulos"]["posicionarse_en_la_pagina"] ?? null;
    $ocultar_id_tabla = $data["modulos"]["ocultar_id_tabla"];
    $nombre_columnas = $data["modulos"]["nombre_columnas"] ?? null;
    $nuevo_nombre_columnas = $data["modulos"]["nuevo_nombre_columnas"] ?? null;
    $nombre_campos = $data["modulos"]["nombre_campos"] ?? null;
    $nuevo_nombre_campos = $data["modulos"]["nuevo_nombre_campos"] ?? null;
    $tipo_de_filtro = $data["modulos"]["tipo_de_filtro"] ?? null;
    $totalRecordsInfo = $data["modulos"]["totalRecordsInfo"] ?? null;
    $area_protegida_por_login = $data["modulos"]["area_protegida_por_login"];
    $posicion_filtro = $data["modulos"]["posicion_filtro"] ?? null;
    $file_callback = $data["modulos"]["file_callback"];
    $type_callback = $data["modulos"]["type_callback"];
    $type_fields = $data["modulos"]["type_fields"];
    $text_no_data = $data["modulos"]["text_no_data"] ?? null;
    $send_email = $data["modulos"]["send_email"];
    $activate_pdf = $data["modulos"]["activate_pdf"];
    $logo_pdf = $data["modulos"]["logo_pdf"] ?? null;
    $marca_de_agua_pdf = $data["modulos"]["marca_de_agua_pdf"] ?? null;
    $consulta_pdf = $data["modulos"]["consulta_pdf"] ?? null;
    $estructura_de_columnas_y_campos = $data["modulos"]["estructura_de_columnas_y_campos"] ?? null;
    $campos_no_requeridos = isset($data["modulos"]["campos_no_requeridos"]) ? $data["modulos"]["campos_no_requeridos"] : null;
    $ocultar_label = isset($data["modulos"]["ocultar_label"]) ? $data["modulos"]["ocultar_label"] : null;
    $valor_predeterminado_de_campo = isset($data["modulos"]["valor_predeterminado_de_campo"]) ? $data["modulos"]["valor_predeterminado_de_campo"] : null;
    $activar_autosugerencias = $data["modulos"]["activar_autosugerencias"];

    if(empty($crud_type)){
        echo "Lo siento, El Tipo de Módulo es Obligatorio";
        die();
    }

    if(empty($tabla)){
        echo "Lo siento, El Nombre Tabla Base de Datos es Obligatoria";
        die();
    }

    if(empty($nombre_modulo)){
        echo "Lo siento, El Nombre Módulo es Obligatoria";
        die();
    }

    if(empty($controller_name)){
        echo "Lo siento, El nombre del controlador es Obligatorio";
        die();
    }

    if(empty($name_view)){
        echo "Lo siento, El Nombre de la Vista es Obligatorio";
        die();
    }

    $queryfy = $obj->getQueryfyObj();
   
    // Validación para el campo "nombre_modulo"
    $queryfy->where("nombre_modulo", $nombre_modulo);
    $db_result = $queryfy->select("modulos");
    if ($db_result) {
        echo "Lo siento, el nombre del módulo ingresado ya existe. Pruebe con otro diferente.";
        die();
    }

    // Validación para el campo "controller_name"
    $queryfy = $obj->getQueryfyObj(); // Restablecemos el objeto para una nueva consulta
    $queryfy->where("controller_name", $controller_name);
    $db_result = $queryfy->select("modulos");
    if ($db_result) {
        echo "Lo siento, el nombre del controlador ya existe. Pruebe con otro diferente.";
        die();
    }

    // Validación para el campo "name_view"
    $queryfy = $obj->getQueryfyObj(); // Restablecemos el objeto para una nueva consulta
    $queryfy->where("name_view", $name_view);
    $db_result = $queryfy->select("modulos");
    if ($db_result) {
        echo "Lo siento, el nombre de la vista ya existe. Pruebe con otro diferente.";
        die();
    }

    $queryfy = $obj->getQueryfyObj(); // Restablecemos el objeto para una nueva consulta
    $queryfy->where("file_callback", $file_callback);
    $db_result = $queryfy->select("modulos");
    if ($db_result) {
        echo "Lo siento, el nombre de Archivo de Devolución de llamada ya existe. Pruebe con otro diferente.";
        die();
    }

    if($template_fields == "Si" && empty($estructura_de_columnas_y_campos)){
        echo "Lo siento, Campos y Columnas HTML es Obligatorio";
        die();
    }

    if ($crud_type == "SQL") {
        $crudService = new App\Services\CrudService();
        $crudService->createCrud(
            $tabla, 
            $id_tabla, 
            $crud_type, 
            null, 
            $controller_name,
            $name_view, 
            $template_fields,
            $active_filter, 
            $mostrar_campos_filtro,
            $tipo_de_filtro,
            $clone_row, 
            $active_popup, 
            $active_search, 
            $activate_deleteMultipleBtn, 
            $button_add, 
            $actions_buttons_grid, 
            null, 
            $activate_nested_table,
            $buttons_actions,
            $refrescar_grilla,
            $encryption,
            $mostrar_campos_busqueda,
            $mostrar_columnas_grilla,
            $mostrar_campos_formulario,
            $activar_recaptcha,
            $sitekey_recaptcha,
            $sitesecret_repatcha,
            $function_filter_and_search,
            $mostrar_campos_formulario_editar,
            $posicion_botones_accion_grilla,
            $mostrar_columna_acciones_grilla,
            $campos_requeridos,
            $activar_numeracion_columnas,
            $activar_registros_por_pagina,
            $nombre_modulo,
            $totalRecordsInfo,
            $area_protegida_por_login,
            $posicion_filtro,
            $file_callback,
            $type_callback,
            $text_no_data,
            $estructura_de_columnas_y_campos,
            $campos_no_requeridos,
            $ocultar_label,
            $valor_predeterminado_de_campo,
            $activar_autosugerencias
        );
    } 
    
    if ($crud_type == "CRUD") {
        $crudService = new App\Services\CrudService();
        $crudService->createCrud(
            $tabla, 
            $id_tabla,
            $crud_type, 
            null, 
            $controller_name,
            $name_view, 
            $template_fields,
            $active_filter, 
            $mostrar_campos_filtro,
            $tipo_de_filtro,
            $clone_row, 
            $active_popup, 
            $active_search, 
            $activate_deleteMultipleBtn, 
            $button_add,
            $actions_buttons_grid, 
            null, 
            $activate_nested_table,
            $buttons_actions,
            $refrescar_grilla,
            $encryption,
            $mostrar_campos_busqueda,
            $mostrar_columnas_grilla,
            $mostrar_campos_formulario,
            $activar_recaptcha,
            $sitekey_recaptcha,
            $sitesecret_repatcha,
            $function_filter_and_search,
            $activar_union_interna,
            $tabla_principal_union,
            $tabla_secundaria_union,
            $campos_relacion_union_tabla_principal,
            $campos_relacion_union_tabla_secundaria,
            $activar_union_izquierda,
            $tabla_principal_union_izquierda,
            $tabla_secundaria_union_izquierda,
            $campos_relacion_union_tabla_principal_izquierda,
            $campos_relacion_union_tabla_secundaria_izquierda,
            $mostrar_campos_formulario_editar,
            $posicion_botones_accion_grilla,
            $mostrar_columna_acciones_grilla,
            $campos_requeridos,
            $mostrar_paginacion,
            $activar_numeracion_columnas,
            $activar_registros_por_pagina,
            $cantidad_de_registros_por_pagina,
            $activar_edicion_en_linea,
            $nombre_modulo,
            $ordenar_grilla_por,
            $tipo_orden,
            $posicionarse_en_la_pagina,
            $ocultar_id_tabla,
            $nombre_columnas,
            $nuevo_nombre_columnas,
            $nombre_campos,
            $nuevo_nombre_campos,
            $totalRecordsInfo,
            $area_protegida_por_login,
            $posicion_filtro,
            $file_callback,
            $type_callback,
            $type_fields,
            $text_no_data,
            $send_email,
            $estructura_de_columnas_y_campos,
            $campos_no_requeridos,
            $ocultar_label,
            $valor_predeterminado_de_campo,
            $activar_autosugerencias
        );
    }

    if ($crud_type == "Formulario de inserción") {
        $crudService = new App\Services\CrudService();
        $crudService->createCrud(
            $tabla,
            $id_tabla,
            $crud_type, 
            null, 
            $controller_name,
            $name_view, 
            $template_fields,
            $active_filter, 
            $mostrar_campos_filtro,
            $tipo_de_filtro,
            $clone_row, 
            $active_popup, 
            $active_search, 
            $activate_deleteMultipleBtn, 
            $button_add,
            $actions_buttons_grid, 
            null, 
            $activate_nested_table,
            $buttons_actions,
            $refrescar_grilla,
            $encryption,
            $mostrar_campos_busqueda,
            $mostrar_columnas_grilla,
            $mostrar_campos_formulario,
            $activar_recaptcha,
            $sitekey_recaptcha,
            $sitesecret_repatcha,
            $function_filter_and_search,
            $activar_union_interna,
            $tabla_principal_union,
            $tabla_secundaria_union,
            $campos_relacion_union_tabla_principal,
            $campos_relacion_union_tabla_secundaria,
            $activar_union_izquierda,
            $tabla_principal_union_izquierda,
            $tabla_secundaria_union_izquierda,
            $campos_relacion_union_tabla_principal_izquierda,
            $campos_relacion_union_tabla_secundaria_izquierda,
            $mostrar_campos_formulario_editar,
            $posicion_botones_accion_grilla,
            $mostrar_columna_acciones_grilla,
            $campos_requeridos,
            $mostrar_paginacion,
            $activar_numeracion_columnas,
            $activar_registros_por_pagina,
            $cantidad_de_registros_por_pagina,
            $activar_edicion_en_linea,
            $nombre_modulo,
            $ordenar_grilla_por,
            $tipo_orden,
            $posicionarse_en_la_pagina,
            $ocultar_id_tabla,
            $nombre_columnas,
            $nuevo_nombre_columnas,
            $nombre_campos,
            $nuevo_nombre_campos,
            $totalRecordsInfo,
            $area_protegida_por_login,
            $posicion_filtro,
            $file_callback,
            $type_callback,
            $type_fields,
            $text_no_data,
            $send_email,
            $estructura_de_columnas_y_campos,
            $campos_no_requeridos,
            $ocultar_label,
            $valor_predeterminado_de_campo,
            $activar_autosugerencias
        );
    }

    if ($add_menu == "Si") {

        if($area_protegida_por_login == "Si") {
            $area_protegida_por_login = "Si";
        } else {
            $area_protegida_por_login = "No";
        }

        $datamenu = $queryfy->DBQuery("SELECT MAX(orden_menu) as orden FROM menu");
        $newOrdenMenu = $datamenu[0]["orden"] + 1;

        $nombre_modulo_metodo = limpiarTexto($nombre_modulo);

        $fileName =  "app/core/extra_routes.php";
        $phpCode = "\$router->get('/{$controller_name}', '{$controller_name}Controller@{$nombre_modulo_metodo}');";
        
        generatePHPFile($fileName, $phpCode);

        $queryfy->insert("menu", array(
            "nombre_menu" => $controller_name,
            "url_menu" => "/" . $controller_name,
            "icono_menu" => "far fa-circle",
            "submenu" => "No",
            "orden_menu" => $newOrdenMenu,
            "area_protegida_menu" => $area_protegida_por_login
        ));

        $id_menu = $queryfy->lastInsertId;

        $queryfy->insert("usuario_menu", array(
            "id_usuario" => $id_sesion_usuario,
            "id_menu" => $id_menu,
            "visibilidad_menu" => "Mostrar"
        ));
    }

    $newdata = array();
    $newdata["modulos"]["tabla"] = $tabla;
    $newdata["modulos"]["id_tabla"] = $id_tabla;
    $newdata["modulos"]["crud_type"] = $crud_type;
    $newdata["modulos"]["query"] = $query_db;
    $newdata["modulos"]["controller_name"] = ucfirst($controller_name);
    $newdata["modulos"]["name_view"] = $name_view;
    $newdata["modulos"]["add_menu"] = $add_menu;
    $newdata["modulos"]["template_fields"] = $template_fields;
    $newdata["modulos"]["id_menu"] = $id_menu ?? null;
    $newdata["modulos"]["active_filter"] = $active_filter;
    $newdata["modulos"]["mostrar_campos_filtro"] = $mostrar_campos_filtro;
    $newdata["modulos"]["tipo_de_filtro"] = $tipo_de_filtro;
    $newdata["modulos"]["clone_row"] = $clone_row;
    $newdata["modulos"]["active_popup"] = $active_popup;
    $newdata["modulos"]["active_search"] = $active_search;
    $newdata["modulos"]["activate_deleteMultipleBtn"] = $activate_deleteMultipleBtn;
    $newdata["modulos"]["button_add"] = $button_add;
    $newdata["modulos"]["actions_buttons_grid"] = $actions_buttons_grid;
    $newdata["modulos"]["activate_nested_table"] = $activate_nested_table;
    $newdata["modulos"]["buttons_actions"] = $buttons_actions;
    $newdata["modulos"]["refrescar_grilla"] = $refrescar_grilla;
    $newdata["modulos"]["activate_pdf"] = $activate_pdf;
    $newdata["modulos"]["logo_pdf"] = $logo_pdf;
    $newdata["modulos"]["marca_de_agua_pdf"] = $marca_de_agua_pdf;
    $newdata["modulos"]["consulta_pdf"] = $consulta_pdf;
    $newdata["modulos"]["encryption"] = $encryption;
    $newdata["modulos"]["mostrar_campos_busqueda"] = $mostrar_campos_busqueda;
    $newdata["modulos"]["mostrar_columnas_grilla"] = $mostrar_columnas_grilla;
    $newdata["modulos"]["mostrar_campos_formulario"] = $mostrar_campos_formulario;
    $newdata["modulos"]["activar_recaptcha"] = $activar_recaptcha;
    $newdata["modulos"]["sitekey_recaptcha"] = $sitekey_recaptcha;
    $newdata["modulos"]["sitesecret_repatcha"] = $sitesecret_repatcha;
    $newdata["modulos"]["function_filter_and_search"] = $function_filter_and_search;
    $newdata["modulos"]["activar_union_interna"] = $activar_union_interna;
    $newdata["modulos"]["tabla_principal_union"] = $tabla_principal_union;
    $newdata["modulos"]["tabla_secundaria_union"] = $tabla_secundaria_union;
    $newdata["modulos"]["campos_relacion_union_tabla_principal"] = $campos_relacion_union_tabla_principal;
    $newdata["modulos"]["campos_relacion_union_tabla_secundaria"] = $campos_relacion_union_tabla_secundaria;
    $newdata["modulos"]["activar_union_izquierda"] = $activar_union_izquierda;
    $newdata["modulos"]["tabla_principal_union_izquierda"] = $tabla_principal_union_izquierda;
    $newdata["modulos"]["tabla_secundaria_union_izquierda"] = $tabla_secundaria_union_izquierda;
    $newdata["modulos"]["campos_relacion_union_tabla_principal_izquierda"] = $campos_relacion_union_tabla_principal_izquierda;
    $newdata["modulos"]["campos_relacion_union_tabla_secundaria_izquierda"] = $campos_relacion_union_tabla_secundaria_izquierda;
    $newdata["modulos"]["mostrar_campos_formulario_editar"] = $mostrar_campos_formulario_editar;
    $newdata["modulos"]["posicion_botones_accion_grilla"] = $posicion_botones_accion_grilla;
    $newdata["modulos"]["mostrar_columna_acciones_grilla"] = $mostrar_columna_acciones_grilla;
    $newdata["modulos"]["campos_requeridos"] = $campos_requeridos;
    $newdata["modulos"]["mostrar_paginacion"] = $mostrar_paginacion;
    $newdata["modulos"]["activar_numeracion_columnas"] = $activar_numeracion_columnas;
    $newdata["modulos"]["activar_registros_por_pagina"] = $activar_registros_por_pagina;
    $newdata["modulos"]["cantidad_de_registros_por_pagina"] = $cantidad_de_registros_por_pagina;
    $newdata["modulos"]["activar_edicion_en_linea"] = $activar_edicion_en_linea;
    $newdata["modulos"]["nombre_modulo"] = $nombre_modulo;
    $newdata["modulos"]["ordenar_grilla_por"] = $ordenar_grilla_por;
    $newdata["modulos"]["tipo_orden"] = $tipo_orden;
    $newdata["modulos"]["posicionarse_en_la_pagina"] = $posicionarse_en_la_pagina;
    $newdata["modulos"]["ocultar_id_tabla"] = $ocultar_id_tabla;
    $newdata["modulos"]["nombre_columnas"] = $nombre_columnas;
    $newdata["modulos"]["nuevo_nombre_columnas"] = $nuevo_nombre_columnas;
    $newdata["modulos"]["nombre_campos"] = $nombre_campos;
    $newdata["modulos"]["nuevo_nombre_campos"] = $nuevo_nombre_campos;
    $newdata["modulos"]["totalRecordsInfo"] = $totalRecordsInfo;
    $newdata["modulos"]["area_protegida_por_login"] = $area_protegida_por_login;
    $newdata["modulos"]["posicion_filtro"] = $posicion_filtro;
    $newdata["modulos"]["file_callback"] = $file_callback;
    $newdata["modulos"]["type_callback"] = $type_callback;
    $newdata["modulos"]["type_fields"] = $type_fields;
    $newdata["modulos"]["text_no_data"] = $text_no_data;
    $newdata["modulos"]["send_email"] = $send_email;
    $newdata["modulos"]["estructura_de_columnas_y_campos"] = $estructura_de_columnas_y_campos;
    $newdata["modulos"]["campos_no_requeridos"] = $campos_no_requeridos;
    $newdata["modulos"]["ocultar_label"] = $ocultar_label;
    $newdata["modulos"]["valor_predeterminado_de_campo"] = $valor_predeterminado_de_campo;
    $newdata["modulos"]["activar_autosugerencias"] = $activar_autosugerencias;

    return $newdata;
}

function limpiarTexto($texto) {
    // Reemplazar espacios con guiones bajos
    $texto = str_replace(' ', '_', $texto);

    // Eliminar acentos
    $texto = strtr($texto, [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
        'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
        'ñ' => 'n', 'Ñ' => 'N'
    ]);
    
    // Eliminar cualquier carácter no alfanumérico (opcional)
    $texto = preg_replace('/[^A-Za-z0-9_]/', '', $texto);
    
    return $texto;
}

function generatePHPFile($fileName, $phpCode) {
    // Ruta específica para el directorio raíz de `\artify`
    $filePath = __DIR__ . '/../../../' . $fileName;

    // Leer el contenido actual del archivo
    $currentContent = file_exists($filePath) ? file_get_contents($filePath) : '';

    // Si el archivo ya contiene el bloque de función, añadimos la nueva ruta dentro
    if (strpos($currentContent, 'return function(ArtifyRouter $router)') !== false) {
        // Agregar la nueva ruta antes del cierre `};`
        $updatedContent = preg_replace(
            '/(return function\(ArtifyRouter \$router\) \{)(.*)(\};)/s',
            "$1$2\n    $phpCode\n$3",
            $currentContent
        );
    } else {
        // Si el archivo no contiene el bloque, crear todo el bloque de código desde cero
        $updatedContent = "<?php\n\nuse App\\core\\ArtifyRouter;\n\nreturn function(ArtifyRouter \$router) {\n    $phpCode\n};";
    }

    // Guardar el contenido actualizado en el archivo
    file_put_contents($filePath, $updatedContent);
}

function deleteRouteFromFile($controller_name, $name_view) {
    $fileName = "app/core/extra_routes.php";
    $filePath = __DIR__ . '/../../../' . $fileName;
    
    // Verificar si el archivo existe antes de proceder
    if (!file_exists($filePath)) {
        echo "El archivo $fileName no existe.";
        return;
    }

    // Leer el contenido del archivo línea por línea
    $lines = file($filePath);
    $phpCode = "\$router->get('/{$controller_name}', '{$controller_name}Controller@{$name_view}');";

    // Filtrar las líneas para excluir la que contiene la ruta que queremos eliminar
    $updatedLines = array_filter($lines, function ($line) use ($phpCode) {
        return trim($line) !== $phpCode;
    });

    // Guardar el contenido actualizado en el archivo
    if (count($lines) !== count($updatedLines)) {
        file_put_contents($filePath, implode("", $updatedLines));
        echo "La ruta ha sido eliminada.";
    } else {
        echo "No se encontró la ruta especificada para eliminar.";
    }
}

function despues_de_insertar_modulos($data, $obj) {
    $id_modulos = $data;

    // Verificamos si las variables POST son arrays, si no, las dejamos en null.
    $nivel_db = isset($_POST["nivel_db"]) && is_array($_POST["nivel_db"]) 
        ? $_POST["nivel_db"] : [];

    $tabla_db = isset($_POST["tabla_db"]) && is_array($_POST["tabla_db"]) 
        ? $_POST["tabla_db"] : [];

    $consulta_crear_tabla = isset($_POST["consulta_crear_tabla"]) && is_array($_POST["consulta_crear_tabla"]) 
        ? $_POST["consulta_crear_tabla"] : [];

    $count = count($nivel_db);
    if ($count !== count($tabla_db) || $count !== count($consulta_crear_tabla)) {
        echo 'Los arrays no tienen la misma cantidad de elementos.';
        die();
    }

    $queryfy = $obj->getQueryfyObj();

    for ($i = 0; $i < $count; $i++) {
        $queryfy->insert("anidada", array(
            "id_modulos" => $id_modulos,
            "nivel_db" => $nivel_db[$i],
            "tabla_db" => $tabla_db[$i],
            "consulta_crear_tabla" => $consulta_crear_tabla[$i]
        ));
    }

    return $data;
}

function actualizar_modulos($data, $obj){
    $tabla = $data["modulos"]["tabla"];
    $id_tabla = $data["modulos"]["id_tabla"];
    $crud_type = $data["modulos"]["crud_type"];
    $query_db = $data["modulos"]["query"] ?? null;
    $controller_name = $data["modulos"]["controller_name"];
    $name_view = $data["modulos"]["name_view"];
    $add_menu = $data["modulos"]["add_menu"];
    $template_fields = $data["modulos"]["template_fields"] ?? 'No';
    $active_filter = $data["modulos"]["active_filter"] ?? 'No';
    $mostrar_campos_filtro = $data["modulos"]["mostrar_campos_filtro"] ?? 'No';
    $clone_row = $data["modulos"]["clone_row"] ?? null;
    $active_popup = $data["modulos"]["active_popup"] ?? null;
    $active_search = $data["modulos"]["active_search"] ?? null;
    $activate_deleteMultipleBtn = $data["modulos"]["activate_deleteMultipleBtn"] ?? null;
    $button_add = $data["modulos"]["button_add"] ?? null;
    $actions_buttons_grid = $data["modulos"]["actions_buttons_grid"] ?? null;
    $activate_nested_table = $data["modulos"]["activate_nested_table"];
    $buttons_actions = $data["modulos"]["buttons_actions"] ?? null;
    $refrescar_grilla = $data["modulos"]["refrescar_grilla"] ?? 'No';
    $encryption = $data["modulos"]["encryption"];
    $mostrar_campos_busqueda = $data["modulos"]["mostrar_campos_busqueda"] ?? null;
    $mostrar_columnas_grilla = $data["modulos"]["mostrar_columnas_grilla"] ?? null;
    $mostrar_campos_formulario = $data["modulos"]["mostrar_campos_formulario"];
    $activar_recaptcha = $data["modulos"]["activar_recaptcha"];
    $sitekey_recaptcha = $data["modulos"]["sitekey_recaptcha"] ?? null;
    $sitesecret_repatcha = $data["modulos"]["sitesecret_repatcha"] ?? null;
    $function_filter_and_search = $data["modulos"]["function_filter_and_search"] ?? 'No';
    $activar_union_interna = $data["modulos"]["activar_union_interna"];
    $tabla_principal_union = $data["modulos"]["tabla_principal_union"] ?? null;
    $tabla_secundaria_union = $data["modulos"]["tabla_secundaria_union"] ?? null;
    $campos_relacion_union_tabla_principal = $data["modulos"]["campos_relacion_union_tabla_principal"] ?? null;
    $campos_relacion_union_tabla_secundaria = $data["modulos"]["campos_relacion_union_tabla_secundaria"] ?? null;
    $activar_union_izquierda = $data["modulos"]["activar_union_izquierda"];
    $tabla_principal_union_izquierda = $data["modulos"]["tabla_principal_union_izquierda"] ?? null;
    $tabla_secundaria_union_izquierda = $data["modulos"]["tabla_secundaria_union_izquierda"] ?? null;
    $campos_relacion_union_tabla_principal_izquierda = $data["modulos"]["campos_relacion_union_tabla_principal_izquierda"] ?? null;
    $campos_relacion_union_tabla_secundaria_izquierda = $data["modulos"]["campos_relacion_union_tabla_secundaria_izquierda"] ?? null;
    $mostrar_campos_formulario_editar = $data["modulos"]["mostrar_campos_formulario_editar"] ?? null;
    $posicion_botones_accion_grilla = $data["modulos"]["posicion_botones_accion_grilla"] ?? 'Derecha';
    $mostrar_columna_acciones_grilla = $data["modulos"]["mostrar_columna_acciones_grilla"] ?? 'No';
    $campos_requeridos = $data["modulos"]["campos_requeridos"];
    $mostrar_paginacion = $data["modulos"]["mostrar_paginacion"] ?? 'No';
    $activar_numeracion_columnas = $data["modulos"]["activar_numeracion_columnas"] ?? 'No';
    $activar_registros_por_pagina = $data["modulos"]["activar_registros_por_pagina"] ?? 'No';
    $cantidad_de_registros_por_pagina = $data["modulos"]["cantidad_de_registros_por_pagina"] ?? null;
    $activar_edicion_en_linea = $data["modulos"]["activar_edicion_en_linea"] ?? 'No';
    $nombre_modulo = $data["modulos"]["nombre_modulo"];
    $ordenar_grilla_por = $data["modulos"]["ordenar_grilla_por"] ?? null;
    $tipo_orden = $data["modulos"]["tipo_orden"] ?? null;
    $posicionarse_en_la_pagina = $data["modulos"]["posicionarse_en_la_pagina"] ?? null;
    $ocultar_id_tabla = $data["modulos"]["ocultar_id_tabla"];
    $nombre_columnas = $data["modulos"]["nombre_columnas"] ?? null;
    $nuevo_nombre_columnas = $data["modulos"]["nuevo_nombre_columnas"] ?? null;
    $nombre_campos = $data["modulos"]["nombre_campos"] ?? null;
    $nuevo_nombre_campos = $data["modulos"]["nuevo_nombre_campos"] ?? null;
    $tipo_de_filtro = $data["modulos"]["tipo_de_filtro"] ?? null;
    $totalRecordsInfo = $data["modulos"]["totalRecordsInfo"] ?? null;
    $area_protegida_por_login = $data["modulos"]["area_protegida_por_login"];
    $posicion_filtro = $data["modulos"]["posicion_filtro"] ?? null;
    $file_callback = $data["modulos"]["file_callback"];
    $type_callback = $data["modulos"]["type_callback"];
    $type_fields = $data["modulos"]["type_fields"];
    $text_no_data = $data["modulos"]["text_no_data"] ?? null;
    $send_email = $data["modulos"]["send_email"];
    $activate_pdf = $data["modulos"]["activate_pdf"];
    $logo_pdf = $data["modulos"]["logo_pdf"] ?? null;
    $marca_de_agua_pdf = $data["modulos"]["marca_de_agua_pdf"] ?? null;
    $consulta_pdf = $data["modulos"]["consulta_pdf"] ?? null;
    $estructura_de_columnas_y_campos = $data["modulos"]["estructura_de_columnas_y_campos"] ?? null;
    $campos_no_requeridos = $data["modulos"]["campos_no_requeridos"] ?? null;

    if(empty($crud_type)){
        echo "Lo siento, El Tipo de Módulo es Obligatorio";
        die();
    }

    if(empty($tabla)){
        echo "Lo siento, El Nombre Tabla Base de Datos es Obligatoria";
        die();
    }

    if(empty($nombre_modulo)){
        echo "Lo siento, El Nombre Módulo es Obligatoria";
        die();
    }

    if(empty($controller_name)){
        echo "Lo siento, El nombre del controlador es Obligatorio";
        die();
    }

    if(empty($name_view)){
        echo "Lo siento, El Nombre de la Vista es Obligatorio";
        die();
    }

    $queryfy = $obj->getQueryfyObj();
   
    // Validación para el campo "nombre_modulo"
    $queryfy->where("nombre_modulo", $nombre_modulo);
    $db_result = $queryfy->select("modulos");
    if ($db_result) {
        echo "Lo siento, el nombre del módulo ingresado ya existe. Pruebe con otro diferente.";
        die();
    }

    // Validación para el campo "controller_name"
    $queryfy = $obj->getQueryfyObj(); // Restablecemos el objeto para una nueva consulta
    $queryfy->where("controller_name", $controller_name);
    $db_result = $queryfy->select("modulos");
    if ($db_result) {
        echo "Lo siento, el nombre del controlador ya existe. Pruebe con otro diferente.";
        die();
    }

    // Validación para el campo "name_view"
    $queryfy = $obj->getQueryfyObj(); // Restablecemos el objeto para una nueva consulta
    $queryfy->where("name_view", $name_view);
    $db_result = $queryfy->select("modulos");
    if ($db_result) {
        echo "Lo siento, el nombre de la vista ya existe. Pruebe con otro diferente.";
        die();
    }

    $queryfy = $obj->getQueryfyObj(); // Restablecemos el objeto para una nueva consulta
    $queryfy->where("file_callback", $file_callback);
    $db_result = $queryfy->select("modulos");
    if ($db_result) {
        echo "Lo siento, el nombre de Archivo de Devolución de llamada ya existe. Pruebe con otro diferente.";
        die();
    }

    if($template_fields == "Si" && empty($estructura_de_columnas_y_campos)){
        echo "Lo siento, Campos y Columnas HTML es Obligatorio";
        die();
    }

    if ($crud_type == "SQL") {
        $crudService = new App\Services\CrudService();
        $crudService->createCrud(
            $tabla, 
            $id_tabla, 
            $crud_type, 
            null, 
            $controller_name,
            $name_view, 
            $template_fields,
            $active_filter, 
            $mostrar_campos_filtro,
            $tipo_de_filtro,
            $clone_row, 
            $active_popup, 
            $active_search, 
            $activate_deleteMultipleBtn, 
            $button_add, 
            $actions_buttons_grid, 
            null, 
            $activate_nested_table,
            $buttons_actions,
            $refrescar_grilla,
            $encryption,
            $mostrar_campos_busqueda,
            $mostrar_columnas_grilla,
            $mostrar_campos_formulario,
            $activar_recaptcha,
            $sitekey_recaptcha,
            $sitesecret_repatcha,
            $function_filter_and_search,
            $mostrar_campos_formulario_editar,
            $posicion_botones_accion_grilla,
            $mostrar_columna_acciones_grilla,
            $campos_requeridos,
            $activar_numeracion_columnas,
            $activar_registros_por_pagina,
            $nombre_modulo,
            $totalRecordsInfo,
            $area_protegida_por_login,
            $posicion_filtro,
            $file_callback,
            $type_callback,
            $text_no_data,
            $estructura_de_columnas_y_campos,
            $campos_no_requeridos
        );
    } 
    
    if ($crud_type == "CRUD") {
        $crudService = new App\Services\CrudService();
        $crudService->createCrud(
            $tabla, 
            $id_tabla,
            $crud_type, 
            null, 
            $controller_name,
            $name_view, 
            $template_fields,
            $active_filter, 
            $mostrar_campos_filtro,
            $tipo_de_filtro,
            $clone_row, 
            $active_popup, 
            $active_search, 
            $activate_deleteMultipleBtn, 
            $button_add,
            $actions_buttons_grid, 
            null, 
            $activate_nested_table,
            $buttons_actions,
            $refrescar_grilla,
            $encryption,
            $mostrar_campos_busqueda,
            $mostrar_columnas_grilla,
            $mostrar_campos_formulario,
            $activar_recaptcha,
            $sitekey_recaptcha,
            $sitesecret_repatcha,
            $function_filter_and_search,
            $activar_union_interna,
            $tabla_principal_union,
            $tabla_secundaria_union,
            $campos_relacion_union_tabla_principal,
            $campos_relacion_union_tabla_secundaria,
            $activar_union_izquierda,
            $tabla_principal_union_izquierda,
            $tabla_secundaria_union_izquierda,
            $campos_relacion_union_tabla_principal_izquierda,
            $campos_relacion_union_tabla_secundaria_izquierda,
            $mostrar_campos_formulario_editar,
            $posicion_botones_accion_grilla,
            $mostrar_columna_acciones_grilla,
            $campos_requeridos,
            $mostrar_paginacion,
            $activar_numeracion_columnas,
            $activar_registros_por_pagina,
            $cantidad_de_registros_por_pagina,
            $activar_edicion_en_linea,
            $nombre_modulo,
            $ordenar_grilla_por,
            $tipo_orden,
            $posicionarse_en_la_pagina,
            $ocultar_id_tabla,
            $nombre_columnas,
            $nuevo_nombre_columnas,
            $nombre_campos,
            $nuevo_nombre_campos,
            $totalRecordsInfo,
            $area_protegida_por_login,
            $posicion_filtro,
            $file_callback,
            $type_callback,
            $type_fields,
            $text_no_data,
            $send_email,
            $estructura_de_columnas_y_campos,
            $campos_no_requeridos
        );
    }

    if ($crud_type == "Formulario de inserción") {
        $crudService = new App\Services\CrudService();
        $crudService->createCrud(
            $tabla,
            $id_tabla,
            $crud_type, 
            null, 
            $controller_name,
            $name_view, 
            $template_fields,
            $active_filter, 
            $mostrar_campos_filtro,
            $tipo_de_filtro,
            $clone_row, 
            $active_popup, 
            $active_search, 
            $activate_deleteMultipleBtn, 
            $button_add,
            $actions_buttons_grid, 
            null, 
            $activate_nested_table,
            $buttons_actions,
            $refrescar_grilla,
            $encryption,
            $mostrar_campos_busqueda,
            $mostrar_columnas_grilla,
            $mostrar_campos_formulario,
            $activar_recaptcha,
            $sitekey_recaptcha,
            $sitesecret_repatcha,
            $function_filter_and_search,
            $activar_union_interna,
            $tabla_principal_union,
            $tabla_secundaria_union,
            $campos_relacion_union_tabla_principal,
            $campos_relacion_union_tabla_secundaria,
            $activar_union_izquierda,
            $tabla_principal_union_izquierda,
            $tabla_secundaria_union_izquierda,
            $campos_relacion_union_tabla_principal_izquierda,
            $campos_relacion_union_tabla_secundaria_izquierda,
            $mostrar_campos_formulario_editar,
            $posicion_botones_accion_grilla,
            $mostrar_columna_acciones_grilla,
            $campos_requeridos,
            $mostrar_paginacion,
            $activar_numeracion_columnas,
            $activar_registros_por_pagina,
            $cantidad_de_registros_por_pagina,
            $activar_edicion_en_linea,
            $nombre_modulo,
            $ordenar_grilla_por,
            $tipo_orden,
            $posicionarse_en_la_pagina,
            $ocultar_id_tabla,
            $nombre_columnas,
            $nuevo_nombre_columnas,
            $nombre_campos,
            $nuevo_nombre_campos,
            $totalRecordsInfo,
            $area_protegida_por_login,
            $posicion_filtro,
            $file_callback,
            $type_callback,
            $type_fields,
            $text_no_data,
            $send_email,
            $estructura_de_columnas_y_campos,
            $campos_no_requeridos
        );
    }

    if ($add_menu == "Si") {

        if($area_protegida_por_login == "Si") {
            $area_protegida_por_login = "Si";
        } else {
            $area_protegida_por_login = "No";
        }

        //$datamenu = $queryfy->DBQuery("SELECT MAX(orden_menu) as orden FROM menu");
        //$newOrdenMenu = $datamenu[0]["orden"] + 1;

        //$nombre_modulo_metodo = limpiarTexto($nombre_modulo);

        //$fileName =  "app/core/extra_routes.php";
        //$phpCode = "\$router->get('/{$controller_name}', '{$controller_name}Controller@{$nombre_modulo_metodo}');";
        
        //generatePHPFile($fileName, $phpCode);

        /*$queryfy->insert("menu", array(
            "nombre_menu" => $controller_name,
            "url_menu" => "/" . $controller_name,
            "icono_menu" => "far fa-circle",
            "submenu" => "No",
            "orden_menu" => $newOrdenMenu,
            "area_protegida_menu" => $area_protegida_por_login
        ));

        $id_menu = $queryfy->lastInsertId;

        $queryfy->insert("usuario_menu", array(
            "id_usuario" => $id_sesion_usuario,
            "id_menu" => $id_menu,
            "visibilidad_menu" => "Mostrar"
        ));*/
    }

    $newdata = array();
    $newdata["modulos"]["tabla"] = $tabla;
    $newdata["modulos"]["id_tabla"] = $id_tabla;
    $newdata["modulos"]["crud_type"] = $crud_type;
    $newdata["modulos"]["query"] = $query_db;
    $newdata["modulos"]["controller_name"] = ucfirst($controller_name);
    $newdata["modulos"]["name_view"] = $name_view;
    $newdata["modulos"]["add_menu"] = $add_menu;
    $newdata["modulos"]["template_fields"] = $template_fields;
    $newdata["modulos"]["id_menu"] = $id_menu ?? null;
    $newdata["modulos"]["active_filter"] = $active_filter;
    $newdata["modulos"]["mostrar_campos_filtro"] = $mostrar_campos_filtro;
    $newdata["modulos"]["tipo_de_filtro"] = $tipo_de_filtro;
    $newdata["modulos"]["clone_row"] = $clone_row;
    $newdata["modulos"]["active_popup"] = $active_popup;
    $newdata["modulos"]["active_search"] = $active_search;
    $newdata["modulos"]["activate_deleteMultipleBtn"] = $activate_deleteMultipleBtn;
    $newdata["modulos"]["button_add"] = $button_add;
    $newdata["modulos"]["actions_buttons_grid"] = $actions_buttons_grid;
    $newdata["modulos"]["activate_nested_table"] = $activate_nested_table;
    $newdata["modulos"]["buttons_actions"] = $buttons_actions;
    $newdata["modulos"]["refrescar_grilla"] = $refrescar_grilla;
    $newdata["modulos"]["activate_pdf"] = $activate_pdf;
    $newdata["modulos"]["logo_pdf"] = $logo_pdf;
    $newdata["modulos"]["marca_de_agua_pdf"] = $marca_de_agua_pdf;
    $newdata["modulos"]["consulta_pdf"] = $consulta_pdf;
    $newdata["modulos"]["encryption"] = $encryption;
    $newdata["modulos"]["mostrar_campos_busqueda"] = $mostrar_campos_busqueda;
    $newdata["modulos"]["mostrar_columnas_grilla"] = $mostrar_columnas_grilla;
    $newdata["modulos"]["mostrar_campos_formulario"] = $mostrar_campos_formulario;
    $newdata["modulos"]["activar_recaptcha"] = $activar_recaptcha;
    $newdata["modulos"]["sitekey_recaptcha"] = $sitekey_recaptcha;
    $newdata["modulos"]["sitesecret_repatcha"] = $sitesecret_repatcha;
    $newdata["modulos"]["function_filter_and_search"] = $function_filter_and_search;
    $newdata["modulos"]["activar_union_interna"] = $activar_union_interna;
    $newdata["modulos"]["tabla_principal_union"] = $tabla_principal_union;
    $newdata["modulos"]["tabla_secundaria_union"] = $tabla_secundaria_union;
    $newdata["modulos"]["campos_relacion_union_tabla_principal"] = $campos_relacion_union_tabla_principal;
    $newdata["modulos"]["campos_relacion_union_tabla_secundaria"] = $campos_relacion_union_tabla_secundaria;
    $newdata["modulos"]["activar_union_izquierda"] = $activar_union_izquierda;
    $newdata["modulos"]["tabla_principal_union_izquierda"] = $tabla_principal_union_izquierda;
    $newdata["modulos"]["tabla_secundaria_union_izquierda"] = $tabla_secundaria_union_izquierda;
    $newdata["modulos"]["campos_relacion_union_tabla_principal_izquierda"] = $campos_relacion_union_tabla_principal_izquierda;
    $newdata["modulos"]["campos_relacion_union_tabla_secundaria_izquierda"] = $campos_relacion_union_tabla_secundaria_izquierda;
    $newdata["modulos"]["mostrar_campos_formulario_editar"] = $mostrar_campos_formulario_editar;
    $newdata["modulos"]["posicion_botones_accion_grilla"] = $posicion_botones_accion_grilla;
    $newdata["modulos"]["mostrar_columna_acciones_grilla"] = $mostrar_columna_acciones_grilla;
    $newdata["modulos"]["campos_requeridos"] = $campos_requeridos;
    $newdata["modulos"]["mostrar_paginacion"] = $mostrar_paginacion;
    $newdata["modulos"]["activar_numeracion_columnas"] = $activar_numeracion_columnas;
    $newdata["modulos"]["activar_registros_por_pagina"] = $activar_registros_por_pagina;
    $newdata["modulos"]["cantidad_de_registros_por_pagina"] = $cantidad_de_registros_por_pagina;
    $newdata["modulos"]["activar_edicion_en_linea"] = $activar_edicion_en_linea;
    $newdata["modulos"]["nombre_modulo"] = $nombre_modulo;
    $newdata["modulos"]["ordenar_grilla_por"] = $ordenar_grilla_por;
    $newdata["modulos"]["tipo_orden"] = $tipo_orden;
    $newdata["modulos"]["posicionarse_en_la_pagina"] = $posicionarse_en_la_pagina;
    $newdata["modulos"]["ocultar_id_tabla"] = $ocultar_id_tabla;
    $newdata["modulos"]["nombre_columnas"] = $nombre_columnas;
    $newdata["modulos"]["nuevo_nombre_columnas"] = $nuevo_nombre_columnas;
    $newdata["modulos"]["nombre_campos"] = $nombre_campos;
    $newdata["modulos"]["nuevo_nombre_campos"] = $nuevo_nombre_campos;
    $newdata["modulos"]["totalRecordsInfo"] = $totalRecordsInfo;
    $newdata["modulos"]["area_protegida_por_login"] = $area_protegida_por_login;
    $newdata["modulos"]["posicion_filtro"] = $posicion_filtro;
    $newdata["modulos"]["file_callback"] = $file_callback;
    $newdata["modulos"]["type_callback"] = $type_callback;
    $newdata["modulos"]["type_fields"] = $type_fields;
    $newdata["modulos"]["text_no_data"] = $text_no_data;
    $newdata["modulos"]["send_email"] = $send_email;
    $newdata["modulos"]["estructura_de_columnas_y_campos"] = $estructura_de_columnas_y_campos;
    $newdata["modulos"]["campos_no_requeridos"] = $campos_no_requeridos;

    return $newdata;
}


function eliminar_modulos($data, $obj)
{
    $id = $data["id"];
    $queryfy = $obj->getQueryfyObj();
    $queryfy->where("id_modulos", $id);
    $query = $queryfy->select("modulos");

    $id_menu = $query[0]["id_menu"];

    if (empty($query)) {
       echo "No se encontró ningún módulo con el ID proporcionado.";
    }

    $queryfy->where("id_menu", $id_menu);
    $queryfy->delete("menu");

    $queryfy->where("id_menu", $id_menu);
    $queryfy->delete("usuario_menu");

    $tabla = $query[0]["tabla"];
    $controller_name = $query[0]["controller_name"];
    $nameview = $query[0]["name_view"];
    $file_callback = $query[0]["file_callback"];

    $controllerFilePath = dirname(__DIR__, 3) . '/app/Controllers/' . $controller_name . 'Controller.php';
    $viewFilePath =  dirname(__DIR__, 3) . '/app/Views/' . $nameview . '.php';
    $viewFilePathEdit =  dirname(__DIR__, 3) . '/app/Views/editar_' . $nameview . '.php';
    $viewFilePathAdd =  dirname(__DIR__, 3) . '/app/Views/agregar_' . $nameview . '.php';
    $function_callback = ArtifyABSPATH . $file_callback . '.php';
   
    $filesToDelete = [$controllerFilePath, $viewFilePath, $viewFilePathEdit, $viewFilePathAdd, $function_callback];

    foreach ($filesToDelete as $filePath) {
        if ($filePath && file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Función para eliminar un directorio completo y su contenido
    function eliminar_directorio_completo($dir)
    {
        if (!file_exists($dir)) {
            return false;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $filePath = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($filePath)) {
                eliminar_directorio_completo($filePath);
            } else {
                unlink($filePath);
            }
        }

        return rmdir($dir);
    }

    $templaesCrudDirPath = dirname(__DIR__, 3) . '/app/libs/artify/classes/templates/template_' . $nameview . '/';
    if (file_exists($templaesCrudDirPath) && is_dir($templaesCrudDirPath)) {
        try {
            if (eliminar_directorio_completo($templaesCrudDirPath)) {
                echo "<script>
                     Swal.fire({
                        title: 'Módulo Eliminado con éxito',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });
                </script>";
            } else {
                echo "Error al eliminar el directorio: $templaesCrudDirPath";
            }
        } catch (Exception $e) {
            echo "Error al intentar eliminar el directorio: " . $e->getMessage();
        }
    } else {
        echo "El directorio no existe: $templaesCrudDirPath";
    }

    deleteRouteFromFile($controller_name, $nameview);

    return $data;
}

function editar_perfil($data, $obj){
    $token = $_POST['auth_token'];
    $valid = App\core\Token::verifyFormToken('send_message', $token);
    if (!$valid) {
        echo "El token recibido no es válido";
        die();
    }

    $id     = $data["usuario"]["id"];
    $nombre = $data["usuario"]["nombre"];
    $email  = $data["usuario"]["email"];
    $user   = $data["usuario"]["usuario"];
    $clave  = $data["usuario"]["password"];
    $rol    = $data["usuario"]["idrol"];

    if(empty($nombre)){
        $error_msg = array("message" => "", "error" => "El campo Nombre Completo es obligatorio", "redirectionurl" => "");
        die(json_encode($error_msg));
    } else if(empty($email)){
        $error_msg = array("message" => "", "error" => "El campo Correo Electrónico es obligatorio", "redirectionurl" => "");
        die(json_encode($error_msg));
    } else if(empty($user)){
        $error_msg = array("message" => "", "error" => "El campo Usuario es obligatorio", "redirectionurl" => "");
        die(json_encode($error_msg));
    } else if(empty($clave)){
        $error_msg = array("message" => "", "error" => "El campo Clave de acceso es obligatorio", "redirectionurl" => "");
        die(json_encode($error_msg));
    } else if(empty($rol)){
        $error_msg = array("message" => "", "error" => "El campo Rol es obligatorio", "redirectionurl" => "");
        die(json_encode($error_msg));
    }

    $queryfy = $obj->getQueryfyObj();
    $result = $queryfy->DBQuery("SELECT * FROM usuario WHERE (usuario = :user OR email = :email) AND id != :id", [':user' => $user, ':email' => $email, ':id' => $id]);

    if($result){
        $error_msg = array("message" => "", "error" => "El correo o el usuario ya existe.", "redirectionurl" => "");
        die(json_encode($error_msg));
    } else {

        if(empty($clave)){
            $error_msg = array("message" => "", "error" => "Ingresa una clave para guardar tus datos.", "redirectionurl" => "");
            die(json_encode($error_msg));
        }

        $newdata = array();
        $newdata["usuario"]["nombre"] = $nombre;
        $newdata["usuario"]["usuario"] = $user;
        $newdata["usuario"]["email"] = $email;
        $newdata["usuario"]["avatar"] = basename($data["usuario"]["avatar"]);
        $newdata["usuario"]["password"] = password_hash($clave, PASSWORD_DEFAULT);
        $newdata["usuario"]["token"] = $token;
        $newdata["usuario"]["expiration_token"] = 0;
        $newdata["usuario"]["idrol"] = $rol;
        $newdata["usuario"]["estatus"] = 1;

        return $newdata;
    }
}

function insetar_usuario($data, $obj){
    $token = $_POST['auth_token'];
    $valid = App\core\Token::verifyFormToken('send_message', $token);
    if (!$valid) {
        echo "El token recibido no es válido";
        die();
    }

    $nombre = $data["usuario"]["nombre"];
    $email  = $data["usuario"]["email"];
    $user   = $data["usuario"]["usuario"];
    $clave  = $data["usuario"]["password"];
    $rol    = $data["usuario"]["idrol"];
    $avatar = $data["usuario"]["avatar"];

    if(empty($nombre)){
        $error_msg = array("message" => "", "error" => "El campo Nombre Completo es obligatorio", "redirectionurl" => "");
        die(json_encode($error_msg));
    } else if(empty($email)){
        $error_msg = array("message" => "", "error" => "El campo Correo Electrónico es obligatorio", "redirectionurl" => "");
        die(json_encode($error_msg));
    } else if(empty($user)){
        $error_msg = array("message" => "", "error" => "El campo Usuario es obligatorio", "redirectionurl" => "");
        die(json_encode($error_msg));
    } else if(empty($clave)){
        $error_msg = array("message" => "", "error" => "El campo Clave de acceso es obligatorio", "redirectionurl" => "");
        die(json_encode($error_msg));
    } else if(empty($rol)){
        $error_msg = array("message" => "", "error" => "El campo Rol es obligatorio", "redirectionurl" => "");
        die(json_encode($error_msg));
    }

    $queryfy = $obj->getQueryfyObj();
    $result = $queryfy->DBQuery("SELECT * FROM usuario WHERE usuario = '$user' OR email = '$email'");

    if($result){
        $error_msg = array("message" => "", "error" => "El correo o el usuario ya existe.", "redirectionurl" => "");
        die(json_encode($error_msg));
    } else {
        $newdata = array();
        $newdata["usuario"]["nombre"] = $nombre;
        $newdata["usuario"]["usuario"] = $user;
        $newdata["usuario"]["email"] = $email;
        if (empty($avatar)) {
            $image = ArtifyABSPATH . 'uploads/1710162578_user.png';
            $newdata["usuario"]["avatar"] =  basename($image);
        } else {
            $newdata["usuario"]["avatar"] = basename($avatar);
        }
        $newdata["usuario"]["password"] = password_hash($clave, PASSWORD_DEFAULT);
        $newdata["usuario"]["token"] = $token;
        $newdata["usuario"]["expiration_token"] = 0;
        $newdata["usuario"]["idrol"] = $rol;
        $newdata["usuario"]["estatus"] = 1;

        return $newdata;
    }
}

function editar_usuario($data, $obj){
    $token = $_POST['auth_token'];
    $valid = App\core\Token::verifyFormToken('send_message', $token);
    if (!$valid) {
        echo "El token recibido no es válido";
        die();
    }

    $id     = $data["usuario"]["id"];
    $nombre = $data["usuario"]["nombre"];
    $email  = $data["usuario"]["email"];
    $clave  = $data["usuario"]["password"];
    $user   = $data["usuario"]["usuario"];
    $rol    = $data["usuario"]["idrol"];

    
    if(empty($nombre)){
        $error_msg = array("message" => "", "error" => "El campo Nombre Completo es obligatorio", "redirectionurl" => "");
        die(json_encode($error_msg));
    } else if(empty($email)){
        $error_msg = array("message" => "", "error" => "El campo Correo Electrónico es obligatorio", "redirectionurl" => "");
        die(json_encode($error_msg));
    } else if(empty($user)){
        $error_msg = array("message" => "", "error" => "El campo Usuario es obligatorio", "redirectionurl" => "");
        die(json_encode($error_msg));
    } else if(empty($clave)){
        $error_msg = array("message" => "", "error" => "El campo Clave de acceso es obligatorio", "redirectionurl" => "");
        die(json_encode($error_msg));
    } else if(empty($rol)){
        $error_msg = array("message" => "", "error" => "El campo Rol es obligatorio", "redirectionurl" => "");
        die(json_encode($error_msg));
    }

    $queryfy = $obj->getQueryfyObj();
    $result = $queryfy->DBQuery("SELECT * FROM usuario WHERE (usuario = :user OR email = :email) AND id != :id", [':user' => $user, ':email' => $email, ':id' => $id]);
    
    if ($result) {
        $error_msg = array("message" => "", "error" => "El correo o el usuario ya existe.", "redirectionurl" => "");
        die(json_encode($error_msg));
    } else {
        $newdata = array();
        $newdata["usuario"]["id"] = $id;
        $newdata["usuario"]["nombre"] = $nombre;
        $newdata["usuario"]["usuario"] = $user;
        $newdata["usuario"]["email"] = $email;
        $newdata["usuario"]["avatar"] = basename($data["usuario"]["avatar"]);
        $newdata["usuario"]["password"] = password_hash($clave, PASSWORD_DEFAULT);
        $newdata["usuario"]["token"] = $token;
        $newdata["usuario"]["expiration_token"] = 0;
        $newdata["usuario"]["idrol"] = $rol;
        $newdata["usuario"]["estatus"] = 1;

        return $newdata;
    }
}

function beforeloginCallback($data, $obj) {
    $pass = $data['usuario']['password'];
    $user_or_rut = $data['usuario']['usuario'] ?? $data['usuario']['rut'] ?? null;

    if ($user_or_rut) {
        $queryfy = $obj->getQueryfyObj();
        $field = isset($data['usuario']['rut']) ? "rut" : "usuario";
        $queryfy->where($field, $user_or_rut);
        $hash = $queryfy->select("usuario");

       if ($hash) {
            if (password_verify($pass, $hash[0]['password'])) {
                @session_start();
                $_SESSION["data"] = $data;
                $obj->setLangData("no_data", "Bienvenido");
            } else {
                echo "El usuario o la contraseña ingresada no coinciden";
                die();
            }
        } else {
            if (isset($data['usuario']['rut'])) {
                echo "El RUT ingresado no coincide";
            } else {
                echo "El usuario ingresado no existe";
            }
            die();
        }
    } else {
        echo "Datos erróneos";
        die();
    }

    return $data;
}
 
function insertar_submenu($data, $obj){
    $id_menu = $data["submenu"]["id_menu"];
   
    $queryfy = $obj->getQueryfyObj();
    $queryfy->where("id_menu", $id_menu);
    $result = $queryfy->select("menu");
    
    if($result){
        $queryfy->where("id_menu", $id_menu);
        $queryfy->update("menu", array("submenu"=> "Si"));
    }
    return $data;
}

function modificar_submenu($data, $obj){
    $id_menu = $data["submenu"]["id_menu"];
   
    $queryfy = $obj->getQueryfyObj();
    $queryfy->where("id_menu", $id_menu);
    $result = $queryfy->select("menu");
    
    if($result){
        $queryfy->where("id_menu", $id_menu);
        $queryfy->update("menu", array("submenu"=> "Si"));
    }
    return $data;
}
 
function formatTableMenu($data, $obj){
    if($data){
        for ($i = 0; $i < count($data); $i++) {

            if($data[$i]["submenu"] == "No"){
                $data[$i]["submenu"] = "<div class='badge badge-danger'>".$data[$i]["submenu"]."</div>";
            } else {
                $data[$i]["submenu"] = "<div class='badge badge-success'>".$data[$i]["submenu"]."</div>";
            }

            $data[$i]["orden_menu"] = "<div class='badge badge-success'>".$data[$i]["orden_menu"]."</div>";

            $data[$i]["icono_menu"] = "<i style='font-size: 20px;' class='".$data[$i]["icono_menu"]."'></i>";
            
        }
    }
    return $data;
}


function formatTableSolicitudesapa($data, $obj){
    if($data){
        for ($i = 0; $i < count($data); $i++) {

            if($data[$i]["estado"] == "Resultado"){
                $data[$i]["estado"] = "<div class='badge badge-success'>".$data[$i]["estado"]."</div>";
            } else if($data[$i]["estado"] == "Recepcionado"){
                $data[$i]["estado"] = "<div class='badge badge-warning'>".$data[$i]["estado"]."</div>";
            } else if($data[$i]["estado"] == "Solicitado"){
                $data[$i]["estado"] = "<div class='badge badge-secondary'>".$data[$i]["estado"]."</div>";
            } else {
                $data[$i]["estado"] = "<div class='badge badge-primary'>".$data[$i]["estado"]."</div>";
            }
        }
    }
    return $data;
}

function formatTableCriticos($data, $obj){
    if($data){
        foreach($data as &$items){

            if($items["notificado"] == "si"){
                $items["notificado"] = "<div class='badge badge-success'>".$items["notificado"]."</div>";
            } else {
                $items["notificado"] = "<div class='badge badge-danger'>".$items["notificado"]."</div>";
            }
        }
        return $data;
    } 
}


function formatTableSubMenu($data, $obj){
    if($data){
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]["orden_submenu"] = "<div class='badge badge-success'>".$data[$i]["orden_submenu"]."</div>";

            $data[$i]["icono_submenu"] = "<i style='font-size: 20px;' class='".$data[$i]["icono_submenu"]."'></i>";
            
        }
    }
    return $data;
}


function agregar_profesional($data, $obj){
    $nombre_profesional = $data["profesional"]["nombre_profesional"];
    $apellido_profesional = $data["profesional"]["apellido_profesional"];

    $obj->setLangData("success", "Profesional Agregado con éxito");

    return $data;
}

function resetloginCallback($data, $obj)
{   
    $email = htmlspecialchars($data['usuario']['email']);

    if(empty($email)){
        echo "Ingrese un correo para Recuperar su contraseña";
        die(); 
    } 

    $queryfy = $obj->getQueryfyObj();
    $queryfy->where("email", $email);
    $hash = $queryfy->select("usuario");

    if ($hash) {
        $pass = $queryfy->getRandomPassword(15, true);
        $encrypt = password_hash($pass, PASSWORD_DEFAULT);

        $usuario = $hash[0]["usuario"];

        $queryfy->where("id", $hash[0]["id"]);
        $queryfy->update("usuario", array("password" => $encrypt));

        $emailBody = "tu usuario es: $usuario y tu nueva contraseña es: $pass";
        $subject = "Usuario y Nueva Contraseña de acceso a Tickibot Soporte con IA en tiempo Real";
        $to = $email;

        App\core\DB::PHPMail($to, "daniel.telematico@gmail.com", $subject, $emailBody);
        $obj->setLangData("success", "Correo enviado con éxito");
    }

    return $data;
}
