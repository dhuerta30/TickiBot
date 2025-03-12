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

		if (isset($_SESSION["data"]["usuario"]["usuario"])) {
			$artify = DB::ArtifyCrud();
			$queryfy = $artify->getQueryfyObj();
			$queryfy->where("usuario", $_SESSION["data"]["usuario"]["usuario"]);
			$sesion_users = $queryfy->select("usuario");
			$_SESSION["usuario"] = $sesion_users;
		}

		$Sesusuario = SessionManager::get('usuario');
		if (isset($Sesusuario)) {
			Redirect::to("bot");
		}
	}

    public function index(){
        $artify = DB::ArtifyCrud();
		$html_template = '
		<div class="container mt-5">
			<div class="row d-flex justify-content-center">
				<div class="col-md-6">
					<div class="card px-5 py-5 bg-light shadow-lg" id="form1">
						<center><img class="w-25" src="'.$_ENV["BASE_URL"].'theme/img/boot.png"></center>
						<p class="mb-3 mt-3 text-center font-weight-bold">Tickibot Soporte con IA en tiempo Real</p>
						<div class="form-data" v-if="!submitted">
							<div class="form-group">
								<label>Usuario</label>
								{usuario}
								<p class="ertify_help_block help-block form-text with-errors"></p>
							</div>
							<div class="form-group">
								<label>Contraseña</label> 
								{password}
								<p class="ertify_help_block help-block form-text with-errors"></p>
							</div>
							<div class="mb-3"> <button v-on:click.stop.prevent="submit" class="btn btn-primary w-100">Acceder</button> </div>
						</div>
					</div>
				</div>
			</div>
		</div>';
		$artify->set_template($html_template);
		$artify->buttonHide("submitBtn");
		$artify->buttonHide("cancel");
		$artify->fieldTypes("password", "password");
		$artify->addCallback("before_select", "beforeloginCallback");
		$artify->setLangData("login", "Ingresar");
		$login = $artify->dbTable("usuario")->render("selectform");

        View::render('login', ['login' => $login]);
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
		$artify->fieldRenameLable("email", "Correo");
		$artify->fieldAddOnInfo("email", "before", '<div class="input-group-append"><span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope-o"></i></span></div>');
		$artify->addCallback("before_select", "resetloginCallback");
		$artify->formFields(array("email"));
		$artify->setLangData("login", "Recuperar");
		$reset = $artify->dbTable("usuario")->render("selectform");

		View::render('reset', ['reset' => $reset]);
	}
}