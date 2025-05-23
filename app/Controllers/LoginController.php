<?php 

namespace App\Controllers;

use App\core\SessionManager;
use App\core\Token;
use App\core\Redirect;
use App\core\View;
use App\core\DB;
use App\Models\UserModel;

class LoginController {

    public function __construct()
	{
		SessionManager::startSession();

		if (isset($_SESSION["data"]["usuario"]["rut"])) {
			$artify = DB::ArtifyCrud();
			$queryfy = $artify->getQueryfyObj();
			$queryfy->where("rut", $_SESSION["data"]["usuario"]["rut"]);
			$sesion_users = $queryfy->select("usuario");
			$_SESSION["usuario"] = $sesion_users;

			Redirect::to("bot");
		}

		if (isset($_SESSION["data"]["usuario"]["usuario"])) {
			$artify = DB::ArtifyCrud();
			$queryfy = $artify->getQueryfyObj();
			$queryfy->where("usuario", $_SESSION["data"]["usuario"]["usuario"]);
			$sesion_users = $queryfy->select("usuario");
			$_SESSION["usuario"] = $sesion_users;

			Redirect::to("bot");
		}
	}

    public function index(){
        $artify = DB::ArtifyCrud();
		$artify->addPlugin("bootstrap-inputmask");
		$html_template = '
		<div class="container mt-5">
			<div class="row d-flex justify-content-center">
				<div class="col-xl-6">
					<div class="card px-5 py-5 bg-light shadow-lg" id="form1">
						<p class="mb-3 mt-3 text-center font-weight-bold">Acceso Funcionarios</p>
						<center><img class="w-25" src="'.$_ENV["BASE_URL"].'theme/img/boot.png"></center>
						<p class="mb-3 mt-3 text-center font-weight-bold">Tickibot Soporte en tiempo Real</p>
						<div class="form-data" v-if="!submitted">
							<div class="form-group text-center">
									<label>¿Cómo desea ingresar al sistema?</label>
									<select class="form-control seleccion_de_acceso">
										<option value="">Seleccione una Opción</option>
										<option value="rut_clave">Con Rut y Contraseña</option>
										<option value="usuario_clave">Con Usuario y Contraseña</option>
									</select>
								</div>	
							<div class="form-group usuario_col d-none">
								<label>Usuario</label>
								{usuario}
								<p class="ertify_help_block help-block form-text with-errors"></p>
							</div>
							<div class="form-group rut_col d-none">
								<label>Rut</label>
								{rut}
								<p class="ertify_help_block help-block form-text with-errors"></p>
							</div>
							<div class="form-group">
								<label>Contraseña</label>
								{password}
								<p class="ertify_help_block help-block form-text with-errors"></p>
							</div>
							<div class="mb-3"> <button v-on:click.stop.prevent="submit" class="btn btn-primary w-100 botones d-none">Acceder</button> </div>
							<a class="btn btn-info btn-block" href="'.$_ENV["BASE_URL"].'registrar">Registrarse</a>
							<a class="btn btn-info btn-block" href="'.$_ENV["BASE_URL"].'recuperar">Recuperar Clave</a>
						</div>
					</div>
				</div>
			</div>
		</div>';
		$artify->set_template($html_template);
		$artify->fieldCssClass("rut", array("rut"));
		$artify->buttonHide("submitBtn");
		$artify->buttonHide("cancel");
		$artify->fieldCssClass("rut", array("rut"));
		$artify->fieldCssClass("usuario", array("usuario"));
		$artify->fieldTypes("password", "password");
		$artify->addCallback("before_select", "beforeloginCallback");
		$artify->setLangData("login", "Ingresar");
		$login = $artify->dbTable("usuario")->render("selectform");
		$mask = $artify->loadPluginJsCode("bootstrap-inputmask",".rut", array(
            "mask"=> "'9{1,2}.9{3}.9{3}-(9|k|K)'",
            "casing" => "'upper'",
            "clearIncomplete" => "true",
            "numericInput"=> "true", 
            "positionCaretOnClick" => "'none'"
        ));

        View::render('login', ['login' => $login, 'mask' => $mask]);
    }

	public function users()
	{
		$users = new UserModel();
		$result = $users->select_users();

		echo json_encode($result);
	}

    public function salir()
	{
		SessionManager::startSession();
		SessionManager::destroy();

		Redirect::to("login");
	}

    public function reset()
	{
		$artify = DB::ArtifyCrud();
		$html_template = '
		<div class="container mt-5">
			<div class="row d-flex justify-content-center">
				<div class="col-xl-6">
					<div class="card px-5 py-5 bg-light shadow-lg" id="form1">
						<p class="mb-3 mt-3 text-center font-weight-bold">Recuperar Clave Funcionarios</p>
						<center><img class="w-25" src="'.$_ENV["BASE_URL"].'theme/img/boot.png"></center>
						<p class="mb-3 mt-3 text-center font-weight-bold">Tickibot Soporte en tiempo Real</p>
						<div class="form-data" v-if="!submitted">
							<div class="form-group">
								<label>Correo</label>
								{email}
								<p class="mt-2 font-weight-bold text-center">Ingresa tu correo para recuperar tu clave</p>
								<p class="ertify_help_block help-block form-text with-errors"></p>
							</div>
							<div class="mb-2"> <button v-on:click.stop.prevent="submit" class="btn btn-primary w-100">Recuperar</button> </div>
							<a class="btn btn-info btn-block" href="'.$_ENV["BASE_URL"].'login">Acceder</a>
							<a class="btn btn-info btn-block" href="'.$_ENV["BASE_URL"].'registrar">Registrarse</a>
						</div>
					</div>
				</div>
			</div>
		</div>';
		$artify->set_template($html_template);
		$artify->buttonHide("submitBtn");
		$artify->buttonHide("cancel");
		$artify->fieldRenameLable("email", "Correo");
		$artify->fieldAddOnInfo("email", "before", '<div class="input-group-append"><span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope-o"></i></span></div>');
		$artify->addCallback("before_select", "resetloginCallback");
		$artify->formFields(array("email"));
		$artify->setLangData("login", "Recuperar");
		$reset = $artify->dbTable("usuario")->render("selectform");

		View::render('reset', ['reset' => $reset]);
	}
}