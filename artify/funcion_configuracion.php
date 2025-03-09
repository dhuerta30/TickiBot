<?php

function before_actualizar_configuracion($data, $obj){
    $data["configuracion"]["logo_login"] = basename($data["configuracion"]["logo_login"]);
    $data["configuracion"]["logo_panel"] = basename($data["configuracion"]["logo_panel"]);
    return $data;
}

function after_actualizar_configuracion($data, $obj){

    return $data;
}
                        
