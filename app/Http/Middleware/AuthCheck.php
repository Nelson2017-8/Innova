<?php

namespace App\Http\Middleware;

use Closure;

class AuthCheck
{
    public function handle($request, Closure $next)
    {
		if (!isset( $_SESSION )){
			session_start();
		}
		if ( !empty($_SESSION['id']) ){
			return redirect()->route('dashboard.index');
		}
        return $next($request);
    }
}
