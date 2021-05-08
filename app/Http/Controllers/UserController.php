<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\User;
use App\PasswordReset;
use App\Http\Controllers\TokenController;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\AuthenticateController;

/*
	CONTROLADOR PARA EL MANEJO DE USUARIOS. COMPLENDE OPERACIONES COMO:
		* INICIO DE SESIÓN
		* REGISTRO DE USUARIO
		* RECUPERACIÓN DE CONTRASEÑA
		* VALIDADCIONES NECESARIAS PARA LOS PROCESOS ANTERIORES
*/
class UserController extends Controller
{
	protected $user = array();
    protected $auth = '';
    protected $authRoute = 'dashboard.index';

    // REGISTRA LA VARIABLE $this->user Y COMIENZA LA SESSIÓN
    public function __construct(){
        $this->auth = new AuthenticateController();
        $this->user = $this->auth->start();

    }

    // VISTA DE LOGIN
    public function getLogin(){
        if ( is_array($this->user) == true ) { return $this->auth->redirect($this->authRoute); }
        return view('login');
    }
    // VISTA DE REGISTRO
    public function getRegister(){
        if ( is_array($this->user) == true ) { return $this->auth->redirect($this->authRoute); }
        return view('register');
    }
    // VISTA DE RECUPERAR CONTRASEÑA
    public function getPassReset(){
        if ( is_array($this->user) == true ) { return $this->auth->redirect($this->authRoute); }
        return view('password_reset');
    }

	// MÉTODO DE INICIO DE SESIÓN
    public function login(Request $request){

		$user = User::where(['email' => $request->email,  'password' => md5($request->password) ])->get();

		if ( isset($user[0]) ) {
			// EL USUARIO EXISTE

			$_SESSION['id'] = $user[0]->getEncrypIdAttribute();
			$_SESSION['typeuser'] = $user[0]->typeuser;
			$_SESSION['email'] = $user[0]->email;
			$_SESSION['username'] = $user[0]->username;
			$_SESSION['date'] = $user[0]->updated_at;
			$_SESSION['data'] = array(
				// DATA GENERAL
				'logo' => asset('img/logo.png'),
				'logo-180' => asset('img/logo-180.png'),
				'logo-320' => asset('img/logo-320.png'),
				'favicon' => asset('img/favicon.ico'),
				'title' => 'Innova C.A.',
				'title-all' => 'Innova Carpintería y Diseños C.A.',
				'address' => 'Ubanización Industrial la Estacia',
				'stacte' => 'Estado Miranda',
				'zip_postal' => '1071',

				// DESARROLLADORES
				'developers' => [
					0 => [
						'name' => 'Nelson Portillo',
						'email' => 'nelsonportillo982@gmail.com'
					],
					1 => [
						'name' => 'Paul Castaño',
						'email' => 'paulcastanio@gmail.com'
					]
				]
			);
			return JsonResponse::create([
				'redirect' => route("dashboard.index"),
				'result' => 'success',
				'messenger' => 'Login éxitoso',
			]);

		}else{
			// EL USUARIO NO EXISTE
			return view("login", [
				'err' => [0 => "El usuario no existe o las credenciales son incorrectas"]
			]);

		}
	}

	// MÉTODO PARA RECUPERAR CONTRASEÑA: ENVIA EMAIL Y GENERA TOKEN
	// NOTA: ACTUALMENTE NO ENVIA EMAIL
	public function forgotten(Request $request){
		echo 'Ya se le ha enviado un correo de recuperación a: '.$request->email;
		$user = User::where(['email' => $request->email])->get();

		if ( isset($user[0]) ) {
			$token = TokenController::obtenToken(60);
			$passReset = new PasswordReset();
			$passReset->email = $request->email;
			$passReset->token = $token;
			$passReset->dateReset = date("Y-m-d H:i:s");
			$passReset->save();

			echo "<br><br><br>PERO como es un entorno de prueba realmente no se envio, porque no esta configurado un cliente de correo electronico, así que por favor, ingrese al siguiente link para recuperar su cuenta: ";
			echo "<a href='".route('rPasswordreset', $passReset->token)."'> Recuperar Contraseña </a>";

		}else{
			echo '<br><br>Usuario no encontrado';
		}

	}

	// MÉTODO PARA RECUPERAR CONTRASEÑA: VERIFICA EL TOKEN GENERADO PREVIAMENTE Y MUESTRA EL
	// FORMULARIO PARA CAMBIAR LA CONTRASEÑA
	public function passwordReset($token){
		$user = PasswordReset::where(['token' => $token])->get();

		if ( isset($user[0]) ) {
			return view('form_passReset', ['user' => $user[0], 'token' => $token]);
		}else{
			echo "Error: No se puede procesar su solicitud, puede que su token de seguridad halla vencido o es inválido";
		}

	}

	// MÉTODO PARA RECUPERAR CONTRASEÑA: VERIFICA SI EL USUARIO EXISTE Y EL TOKEN ES VÁLIDO,
	// SI ES ASI GUARDA LA NUEVA CONTRASEÑA
	// SINO TE ENVIA A LA PAGINA DE CAMBIAR CONTRASEÑA Y MUESTRA UN MENSAJE DE ERROR
	public function saveNewPassword(Request $request){
		// EN CASO DE ERROR
		// $user = PasswordReset::where(['email' => 'emailNoValid0ParaGeneralDeError', 'token' => $request->securityToken])->get();
		$user = PasswordReset::where(['email' => $request->email, 'token' => $request->securityToken])->get();
		if ( isset($user[0]) ) {
			$user = User::where(['email' => $request->email])->get();
			$user[0]->password = md5($request->password);
			$user[0]->save();
			if ( isset($_SESSION['errSaveNewPassword']) ) {
        		unset($_SESSION['errSaveNewPassword']);
        		unset($_SESSION['recovePassEmail']);
        		unset($_SESSION['recovePassToken']);
        	}
			return view("success_passReset");
		}else{
			$_SESSION['errSaveNewPassword'] = 'El usuario no existe, si el problema persiste comuniquese con el equipo técnico';
			$_SESSION['recovePassEmail'] = $request->email;
            $_SESSION['recovePassToken'] = $request->securityToken;

			return redirect()->route("errSaveNewPassword");

		}

	}

	// MÉTODO PARA RECUPERAR CONTRASEÑA: SI EL MÉTODO ANTERIOR DICE QUE NO ENCUENTRA EL USUARIO
	// TE ENVIA AQUÍ, TE MUESTRA UN MENSAJE DE ERROR Y LA PAGINA DE CAMBIAR CONTRASEÑA NUEVAMENTE
	public function errSaveNewPassword(){
        if ( !isset($_SESSION['errSaveNewPassword']) ) {
            return redirect()->route('login');
        }else{
        	$err = [0 => $_SESSION['errSaveNewPassword']];
        	$user = array(
                'email' => $_SESSION['recovePassEmail'],
            );
            return view("form_passReset", [
            	'err' => $err,
            	'user' => $user,
            	'token' => $_SESSION['recovePassToken']
			]);
        }
    }

    // MÉTODO PARA REGISTRAR USUARIO
    public function register(UserRequest $request){
		$user = new User($request->all());
		$user->password = md5($request->password);
		$user->remember_token = 'false';
		$user->save();
		return $this->login($request);
	}
	// MÉTODO PARA REGISTRAR USUARIO
    public static function unsing(UserRequest $request){
		$user = new User($request->all());
		$user->password = md5($request->password);
		$user->remember_token = 'false';
		$user->save();
		return true;
	}
}
