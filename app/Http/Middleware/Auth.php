<?php

namespace App\Http\Middleware;

use App\Http\Controllers\TokenController;
use Closure;
use App\User;

class Auth
{

    public function handle($request, Closure $next)
    {
    	if (!isset( $_SESSION ) ){
    		session_start();
		}
    	if ( isset($_SESSION['id']) ){
			$id = TokenController::desencriptar( $_SESSION['id'] );
			if( empty(User::find($id)) ){
				$_SESSION['notifications'] = array(
					'error' => 'Debe iniciar sesión primero'
				);
				return redirect('login');
			}
		}else{
			return redirect('login');
		}
		return $next($request);
	}
}
