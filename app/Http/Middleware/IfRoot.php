<?php

namespace App\Http\Middleware;

use App\Http\Controllers\TokenController;
use Closure;
use App\User;

class IfRoot
{
    public function handle($request, Closure $next)
    {
		if (!isset( $_SESSION )){
			session_start();
		}
		if ( isset( $_SESSION['id'] ) ){
			$id = TokenController::desencriptar( $_SESSION['id'] );
			if( User::find($id)->typeuser != 'Root' ){
				$_SESSION['notifications'] = array(
					'error' => 'No tienes el acceso requerido, se solicita ser usuario Administrador'
				);
				return redirect()->route('dashboard.index');
			}
		}
        return $next($request);
    }
}
