<?php
date_default_timezone_set(@date_default_timezone_get());
define('PDOCrudABSPATH', dirname(__FILE__) . '/');

//@session_name($_ENV['APP_NAME']);
@session_start();
/*enable this for development purpose */
//ini_set('display_startup_errors', 1);
//ini_set('display_errors', 1);
//error_reporting(-1);
require_once PDOCrudABSPATH . "config/config.php";
spl_autoload_register('pdocrudAutoLoad');

function pdocrudAutoLoad($class) {
    if (file_exists(PDOCrudABSPATH . "classes/" . $class . ".php"))
        require_once PDOCrudABSPATH . "classes/" . $class . ".php";
}

if (isset($_REQUEST["pdocrud_instance"])) {
    $fomplusajax = new PDOCrudAjaxCtrl();
    $fomplusajax->handleRequest();
}

function search_users($data, $obj) {
    if (isset($data["action"]) && $data["action"] === "search") {
        if (isset($data['search_col']) && isset($data['search_text'])) {
            $search_col = $data['search_col'];
            $search_text = $data['search_text'];

            if ($search_col !== 'all') {
                // Si la columna no es 'all', generar WHERE para esa columna
                if($search_col == "complete"){
                    $query = " WHERE CONCAT(first_name,' ',last_name) LIKE '%$search_text%'";
                } else {
                    $query = " WHERE $search_col LIKE '%$search_text%'";
                }
                $obj->setQuery("users", "user_id, CONCAT(first_name,' ',last_name) as complete, user_name, email", "user_id", $query);
            } else if ($search_col === 'all' && !empty($search_text)) {
                // Si la columna es 'all' y el texto de búsqueda no está vacío
                $query = " WHERE CONCAT(first_name, ' ', last_name) LIKE '%$search_text%' OR 
                           user_id LIKE '%$search_text%' OR
                           user_name LIKE '%$search_text%' OR
                           email LIKE '%$search_text%'";
                $obj->setQuery("users", "user_id, CONCAT(first_name,' ',last_name) as complete, user_name, email", "user_id", $query);
            } else if ($search_col === 'all' && empty($search_text)) {
                // Si la columna es 'all' y el texto de búsqueda está vacío, eliminar WHERE
                $query = ""; // Sin filtro
                $obj->setQuery("users", "user_id, CONCAT(first_name,' ',last_name) as complete, user_name, email", "user_id", $query);
            }
        }
    }
    return $data;
}

function antes_del_switch($data, $obj){
    print_r($data);
    return $data;
}


function upload_file($data, $obj){
    print_r($data);
    die();
}


function eliminar_users($data, $obj){
    return $data;
}

function functiones_de_busqueda($data, $obj) {
    if (isset($data["action"]) && $data["action"] == "search") {
        if (isset($data['search_col']) && isset($data['search_text'])) {
            $search_col = $data['search_col'];
            $search_text = $data['search_text'];

            if ($search_col !== 'all' && !empty($search_text)) {
                $obj->where($search_col, "%$search_text%", "LIKE");
            } else {
                $obj->clearWhereConditions();
            }
        }
    }

    return $data;
}


function functiones_de_filtro($data, $obj){
    if (isset($data["action"]) && $data["action"] == "filter") {
        // Limpiar las condiciones WHERE actuales al inicio
        $obj->clearWhereConditions();

        // Aplicar filtros individuales
        if (!empty($data['product_cat_filter'])) {
            $obj->where('product_cat', "%{$data['product_cat_filter']}%", "LIKE", "AND");
        }

        if (!empty($data['ProductLineFilter'])) {
            $obj->where('product_line', "%{$data['ProductLineFilter']}%", "LIKE", "AND");
        }

        if (!empty($data['ProductVendorFilter'])) {
            $obj->where('productVendor', "%{$data['ProductVendorFilter']}%", "LIKE", "AND");
        }
    }

    return $data;
}



function insertar_products($data, $obj){
    $tabla = $obj->langData["tabla"];
    
    $insert = new PDOCrud(true);
    $insert->formStaticFields("unido", "html", "
        <label class='control-label col-form-label'>Unido</label>
        <input type='text' class='form-control pdocrud-form-control pdocrud-text' id='unido' required='1'>
    ");

    $insert->formStaticFields("mybutton", "html", "
        <button type='button' class='btn btn-danger' id='regresar' data-action='ajax_actions'>Regresar</button>
    ");

    $insert->addCallback("before_insert", "add_productos");
    $insert->fieldGroups("fields1",array("product_id","unido"));
    $insert->fieldGroups("fields2",array("productScale","productVendor"));
    $insert->fieldDisplayOrder(array('product_id', 'unido', 'productScale', 'productVendor'));
    $insert->formFields(array('product_id', 'productScale', 'productVendor'));
    echo $insert->dbTable($tabla)->render("insertform");
    return $data;
}

function add_productos($data, $obj){
    print_r($data);
    return $data;
}

function setupPdocrud($isNewInstance = false) {
    $pdocrud = new PDOCrud($isNewInstance);

    $tabla = $pdocrud->getLangData("products");
    $pk = $pdocrud->getLangData("product_id");
    $columnVal = $pdocrud->getLangData("product_id");

    $pdocrud->setLangData("tabla", $tabla)
        ->setLangData("pk", $pk)
        ->setLangData("columnVal", $columnVal);

    $pdocrud->addWhereConditionActionButtons("delete", "productVendor", "!=", array("Second Gear Diecast"));

    $pdocrud->tableHeading("Products");
    $pdocrud->setSettings("encryption", false);
    $pdocrud->setSettings("numberCol", true);
    $pdocrud->setSettings("viewbtn", false);

    $action = "http://google.cl";
    $text = '<i class="fa fa-globe"></i>';
    $attr = array("title"=> "Url", "target"=> "_blank");
    $pdocrud->enqueueBtnActions("url", $action, "url", $text, $pk, $attr, "btn-primary", array("productVendor", "!=", array("Second Gear Diecast")));

    $pdocrud->setSettings("template", "products");
    $pdocrud->setLangData("no_data", "Sin Resultados");

    $param1 = "value1";
    $param2 = "value2";

    $pdocrud->addCallback("before_sql_data", "buscador_products", [$param1, $param2]);
    $pdocrud->addCallback("format_sql_col", "format_sql_col_products");
    $pdocrud->addCallback("before_delete", "eliminar_products");
    $pdocrud->addCallback("before_edit_form_sql", "update_products");
    $pdocrud->addCallback("before_view_form_sql", "view_products");
    $pdocrud->addCallback("format_sql_data", "formatear_grilla_products");
    $pdocrud->addCallback("before_delete_selected", "eliminacion_masiva_products");
    $pdocrud->addCallback("before_insert_form_sql", "insertar_products");

    return $pdocrud;
}

function ajax_custom($data, $obj){
    $callbackParams = isset($_POST['callback_params']) ? $_POST['callback_params'] : [];

    // Aquí puedes manejar los parámetros opcionales
    print_r($callbackParams);

    $obj = setupPdocrud(true);

    $obj->setQuery(
        "SELECT {$obj->getLangData('product_id')},
        CONCAT(product_name,' ',product_line) as unido,
        productScale,
        productVendor
        FROM {$obj->getLangData('products')}"
    );

    echo $obj->render("SQL");
}

function update_products($data, $obj){
    $tabla = $obj->langData["tabla"];
    $pk = $obj->langData["pk"];
    $id = $data["id"];

    $edit = new PDOCrud(true);
    $pdomodel = $edit->getPDOModelObj();
    $pdomodel->where($pk, $id);
    $val = $pdomodel->select($tabla);


    $edit->setPK($pk);
    $edit->formStaticFields("unido", "html", "
        <label class='control-label col-form-label'>Unido</label>
        <input type='text' class='form-control pdocrud-form-control pdocrud-text' id='unido' value='".$val[0]["product_name"]. $val[0]["product_line"]."' required='1'>
    ");
    $edit->formStaticFields("mybutton", "html", "
        <button type='button' class='btn btn-danger' id='regresar' data-action='ajax_actions'>Regresar</button>
    ");
    $edit->fieldGroups("fields1",array("product_id","unido"));
    $edit->fieldGroups("fields2",array("productScale","productVendor"));
    $edit->fieldDisplayOrder(array('product_id', 'unido', 'productScale', 'productVendor'));
    $edit->formFields(array('product_id', 'productScale', 'productVendor'));
    echo $edit->dbTable($tabla)->render("editform", array("id" =>$id));
    return $data;
}

function view_form($data, $obj){
    return $data;
}

function view_products($data, $obj){
    $tabla = $obj->langData["tabla"];
    $pk = $obj->langData["pk"];
    $id = $data["id"];

    $view = new PDOCrud(true);
    $view->setPK($pk);
    $view->addCallback("before_view_form", "view_form");
    $view->setViewColumns(array('product_id', 'productScale', 'productVendor'));
    echo $view->dbTable($tabla)->render("VIEWFORM",array("id" => $id));
    return $data;
}

function eliminar_products($data, $obj){
    $tabla = $obj->langData["tabla"];
    $pk = $obj->langData["pk"];
    $pdomodel = $obj->getPDOModelObj();

    $id = $data["id"];

    if (!empty($id)) {
        $pdomodel->where($pk, $id);
        $pdomodel->delete($tabla);
    }

    return $data;
}

function eliminacion_masiva_products($data, $obj) {
    $tabla = $obj->getLangData("tabla");
    $pk = $obj->getLangData("pk");
    $pdomodel = $obj->getPDOModelObj();

    // Obtener los IDs seleccionados del array
    $selected_ids = $data["selected_ids"];

    // Asegurarse de que $selected_ids no esté vacío
    if (!empty($selected_ids)) {
        // Recorrer cada ID y eliminar el producto correspondiente
        foreach ($selected_ids as $id) {
            $pdomodel->where($pk, $id);
            $pdomodel->delete($tabla);
        }
    }

    // Consulta para verificar los productos restantes
    $query = "SELECT product_id, CONCAT(product_name, ' ', product_line) as unido, productScale, productVendor FROM $tabla";
    $obj->setQuery($query);

    return $data;
}


function format_sql_col_products($data, $obj) {
    $pdomodel = $obj->getPDOModelObj();
    $tabla = $obj->getLangData("tabla");

    $columnNames = $pdomodel->columnNames($tabla);

    // Columnas a mostrar en el orden deseado, incluye concatenaciones
    $include_columns = array('product_id', 'unido', 'productScale', 'productVendor');

    // Plantilla para los detalles de cada columna
    $template = array(
        'colname' => '',
        'tooltip' => '',
        'attr' => '',
        'sort' => '',
        'col' => '',
        'type' => ''
    );

    $default_cols = array();
    foreach ($include_columns as $column) {
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

    // Crear un nuevo array en el orden deseado
    $ordered_default_cols = array();
    foreach ($include_columns as $column) {
        if (isset($default_cols[$column])) {
            $ordered_default_cols[$column] = $default_cols[$column];
        }
    }

    $data = array_merge($ordered_default_cols, $data);
    return $data;
}

function formatear_grilla_products($data, $obj){
    return $data;
}


function formatear_grilla_datos_paciente($data, $obj){
     if($data){
        for ($i = 0; $i < count($data); $i++) {
            if($data[$i]["tiene_adjunto"] == "Si"){
                $data[$i]["tiene_adjunto"] = "<div class='badge badge-success'>" . $data[$i]["tiene_adjunto"] . "</div>";
            } else {
                $data[$i]["tiene_adjunto"] = "<div class='badge badge-info'>".$data[$i]["tiene_adjunto"]."</div>";
            }
        }
    }
    return $data;
}


function buscador_products($data, $obj, $param1 = null, $param2 = null) {

    $pdomodel = $obj->getPDOModelObj();
    $tabla = $obj->getLangData("tabla");

    $whereClause = "";

    if(isset($data["action"]) && $data["action"] == "search"){
        if (isset($data['search_col']) && isset($data['search_text'])) {
                $search_col = $data['search_col'];
                $search_text = $data['search_text'];
            
                // Sanitize inputs to prevent SQL injection
                $search_col = preg_replace('/[^a-zA-Z0-9_]/', '', $search_col);
                $search_text = htmlspecialchars($search_text, ENT_QUOTES, 'UTF-8');
            
            if ($search_text !== '') {
                $_SESSION["search_text"] = $search_text;

                if ($search_col !== 'all') {
                     $obj->where($search_col, $search_text);
                }
            }
        }
    }

    if(isset($data["action"]) && $data["action"] == "filter"){
        if (isset($data["ProductLineFilter"])) {
            $_SESSION["value"] = $data["ProductLineFilter"];
            $filterValue = $_SESSION["value"];
     
            if ($whereClause !== "") {
                $whereClause .= " AND CONCAT(product_name, ' ', product_line) LIKE '%$filterValue%'";
            } else {
                $whereClause = "WHERE CONCAT(product_name, ' ', product_line) LIKE '%$filterValue%'";
            }
     
        } else if (isset($data["page"]) || isset($data["records"])) {
            // Mantener el valor en la sesión para paginación
            $filterValue = isset($_SESSION["value"]) ? $_SESSION["value"] : '';
             
            if ($whereClause !== "") {
                $whereClause .= " AND CONCAT(product_name, ' ', product_line) LIKE '%$filterValue%'";
            } else {
                $whereClause = "WHERE CONCAT(product_name, ' ', product_line) LIKE '%$filterValue%'";
            }
        } else {
            // Limpiar la sesión si no se está aplicando filtro ni paginación
            unset($_SESSION["value"]);
        }
 
        $query = "SELECT product_id, CONCAT(product_name, ' ', product_line) as unido, productScale, productVendor 
        FROM $tabla
        $whereClause";
        $obj->setQuery($query);
    }
    
    //print_r($data);

    return $data;
}