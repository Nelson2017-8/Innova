<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\TokenController;
use App\User;

class AuthenticateController extends Controller
{
    public $user = array();

    // REGISTRA LA VARIABLE $this->user Y COMIENZA LA SESSIÓN
    public function __construct(){
    	return $this->start();
    }

    public function start() {
    	date_default_timezone_set('America/Caracas');
        
        // SI NO EXISTE UNA SESION LA INICIO
        if ( !isset($_SESSION) ) {
            session_start();
        }

        // SI EL USUARIO EXISTE
        if ( !empty($_SESSION['id']) ) {
            
            $this->user = array(
                'id' => $_SESSION['id'], 
                'typeuser' => $_SESSION['typeuser'], 
                'email' => $_SESSION['email'], 
                'username' => $_SESSION['username'], 
                'date' => $_SESSION['date'],

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


            if ( isset(User::where(['id' => TokenController::desencriptar($this->user['id']) ])->get()[0]) ) {
                return $this->user;
            }else{
                session_unset();
                return false;
            }
        }else{
            return false;
        }
    }
    public function startSession() : array {

    	return $this->user;
    }
    public function redirect(string $route) {
        return redirect()->route($route);
    }

    public function isRoot() : bool {
    	if ($this->user['typeuser'] == 'Root') {
    		return true;
    	}else{
    		return false;
        }

    }

    public function notifications() {
    	
    	if (isset($_SESSION['notifications'])) {
            
            $notifications = $_SESSION['notifications'];
            unset($_SESSION['notifications']);
        
        }else{
            $notifications = null;
        }

        return $notifications;
    }
}
