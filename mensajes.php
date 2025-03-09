<?php

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
$artify->formDisplayInPopup();
$artify->tableHeading("Mensajes");
$artify->setSettings("editbtn", true);
$artify->setSettings("viewbtn", false);
$artify->setSettings("delbtn", true);
$artify->setSettings("searchbox", true);
$artify->buttonHide("submitBtnSaveBack");
$artify->crudTableCol(array("id", "user_message", "bot_response"));
$artify->fieldDisplayOrder(array("id", "user_message", "bot_response"));
$artify->colRename("id", "ID");
$artify->colRename("user_message", "Mensaje de usuario");
$artify->colRename("bot_response", "Respuesta de Bot");
$artify->fieldRenameLable("user_message", "Mensaje de usuario");
$artify->fieldRenameLable("bot_response", "Respuesta de Bot");
echo $artify->dbTable("messages")->render();

?>
<div id="artify-ajax-loader">
    <img width="300" src="<?=$_ENV["BASE_URL"]?>artify/images/ajax-loader.gif" class="artify-img-ajax-loader"/>
</div>