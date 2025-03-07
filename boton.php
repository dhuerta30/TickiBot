<?php 
    require "xcrud/xcrud.php";
 
    $xcrud = Xcrud::get_instance();
    $html_template = '
    
    <div class="card">
        <div class="card-header bg-secondary text-white">
            Soporte
        </div>
        <div class="card-body bg-light">
        
            <div class="order-form">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Fecha Llamado:</label>
                            {fecha_llamado}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Hora Llamado:</label>
                            {hora_llamado}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Unidad:</label>
                            {unidad}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Tipo de Problema:</label>
                            {tipo_de_problema}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Describir Problema:</label>
                    {describir_problema}
                </div>
                <div class="form-group">
                    <label class="form-label">Asignado A:</label>
                    {asignado_a}
                </div>
            </div>

        </div>
    </div>';
    $xcrud->set_template($html_template);
    $xcrud->table("soporte");
    $xcrud->change_type("tipo_de_problema", "select", "", array(
        "" => "",
        "ANEXOS" => "ANEXOS",
        "CORREOS" => "CORREOS",
        "DATAS Y VIDEOS CONFERENCIAS" => "DATAS Y VIDEOS CONFERENCIAS",
        "ELECTRICOS" => "ELECTRICOS",
        "GIS" => "GIS",
        "HARDWARE" => "HARDWARE",
        "IMPRESORAS" => "IMPRESORAS",
        "INFINITT" => "INFINITT",
        "INTERNET" => "INTERNET",
        "LICENCIAS MEDICAS" => "LICENCIAS MEDICAS",
        "OTROS" => "OTROS",
        "REDES" => "REDES",
        "RESPALDOS" => "RESPALDOS",
        "SIRH" => "SIRH",
        "SOFTWARE BASICO" => "SOFTWARE BASICO",
        "TRAKCARE" => "TRAKCARE",
        "VIDEO CONFERENCIA" => "VIDEO CONFERENCIA"
    ));
    $xcrud->relation('asignado_a','tecnicos','id_tecnicos','nombre');
    echo $xcrud->render("create");



    $tecnicos = Xcrud::get_instance();
    echo $tecnicos->table("tecnicos")->render();
?>