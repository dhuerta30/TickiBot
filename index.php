<?php 

session_start();

require "artify/artifycrud.php";

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

$artify = new Artify(false, "", "", $settings);
$artify->setSettings("editbtn", true);
$artify->setSettings("viewbtn", false);
$artify->setSettings("delbtn", true);
$artify->setSettings("searchbox", true);
$artify->formFields(array("rut", "clave"));
$html_template = '
   <div class="container mt-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-6">
            <div class="card px-5 py-5 bg-light" id="form1">
                <center><img class="w-25" src="boot.png"></center>
                <div class="form-data" v-if="!submitted">
                    <div class="form-group"> 
                        <label>Rut</label> 
                        {rut}
                        <p class="pdocrud_help_block help-block form-text with-errors"></p>
                    </div>
                    <div class="form-group"> 
                        <label>Contraseña</label> 
                        {clave}
                        <p class="pdocrud_help_block help-block form-text with-errors"></p>
                    </div>
                    <div class="mb-3"> <button v-on:click.stop.prevent="submit" class="btn btn-primary w-100">Acceder</button> </div>
                </div>
            </div>
        </div>
    </div>
</div>';
$artify->buttonHide("submitBtn");
$artify->buttonHide("cancel");
$artify->set_template($html_template);
$artify->addCallback("before_select", "beforeloginCallback");
echo $artify->dbTable("usuario")->render("selectform");
?>
<div id="artify-ajax-loader">
    <img width="300" src="<?=$_ENV["BASE_URL"]?>artify/images/ajax-loader.gif" class="artify-img-ajax-loader"/>
</div>