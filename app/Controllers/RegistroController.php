<?php 

namespace App\Controllers;

use App\core\Token;
use App\core\Redirect;
use App\core\View;
use App\core\DB;
use App\Models\UserModel;

class RegistroController {
    
    public function __construct()
	{

    }

    public function registrar(){
        $artify = DB::ArtifyCrud();
        $html_template = '
		<div class="container mt-5">
			<div class="row d-flex justify-content-center">
				<div class="col-md-6">
					<div class="card px-5 py-5 bg-light shadow-lg" id="form1">
						<p class="mb-3 mt-3 text-center font-weight-bold">Registro Funcionarios</p>
						<center><img class="w-25" src="'.$_ENV["BASE_URL"].'theme/img/boot.png"></center>
						<p class="mb-3 mt-3 text-center font-weight-bold">Tickibot Soporte con IA en tiempo Real</p>
						<div class="form-data" v-if="!submitted">
							<div class="form-group">
								<label>Nombre</label>
								{nombre}
								<p class="ertify_help_block help-block form-text with-errors"></p>
							</div>
							<div class="form-group">
								<label>correo</label>
								{email}
								<p class="ertify_help_block help-block form-text with-errors"></p>
							</div>
                            <div class="form-group">
								<label>Usuario</label>
								{usuario}
								<p class="ertify_help_block help-block form-text with-errors"></p>
							</div>
							<div class="form-group">
								<label>Rut</label>
								{rut}
								<p class="ertify_help_block help-block form-text with-errors"></p>
							</div>
                            <div class="form-group">
								<label>Contraseña</label>
								{password}
								<p class="ertify_help_block help-block form-text with-errors"></p>
							</div>
                            <div class="form-group d-none">
								<label>Rol</label>
								{idrol}
								<p class="ertify_help_block help-block form-text with-errors"></p>
							</div>
                            <div class="form-group d-none">
								<label>Estatus</label>
								{estatus}
								<p class="ertify_help_block help-block form-text with-errors"></p>
							</div>
                            <div class="form-group">
								<label>avatar</label>
								{avatar}
								<p class="ertify_help_block help-block form-text with-errors"></p>
							</div>
							<div class="mb-3"> <button v-on:click.stop.prevent="submit" class="btn btn-primary w-100">Registrar</button> </div>
                            <a class="btn btn-info btn-block" href="'.$_ENV["BASE_URL"].'login">Acceder</a>
							<a class="btn btn-info btn-block" href="'.$_ENV["BASE_URL"].'recuperar">Recuperar Clave</a>
						</div>
					</div>
				</div>
			</div>
		</div>';
		$artify->set_template($html_template);
		$artify->addPlugin("bootstrap-inputmask");
		$artify->fieldCssClass("rut", array("rut"));
        $artify->fieldTypes("avatar", "FILE_NEW");
        $artify->fieldTypes("password", "password");
        $artify->editFormFields(array("nombre", "email", "usuario", "rut", "password", "idrol", "estatus", "avatar"));
        $artify->fieldNotMandatory("avatar");
        $artify->buttonHide("submitBtn");
		$artify->buttonHide("cancel");
        $artify->formFieldValue("idrol", "2");
        $artify->formFieldValue("estatus", "1");
        $artify->addCallback("before_insert", "registrar_funcionarios");
        $render = $artify->dbTable("usuario")->render("insertform");
		$mask = $artify->loadPluginJsCode("bootstrap-inputmask",".rut", array(
            "mask"=> "'9{1,2}.9{3}.9{3}-(9|k|K)'",
            "casing" => "'upper'",
            "clearIncomplete" => "true",
            "numericInput"=> "true", 
            "positionCaretOnClick" => "'none'"
        ));

        View::render('registrar_funcionarios', [
            'render' => $render,
			'mask' => $mask
        ]);
    }
}