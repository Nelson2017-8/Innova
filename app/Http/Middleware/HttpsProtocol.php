<?php

namespace App\Http\Middleware;

use Closure;

class HttpsProtocol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	if (!$request->secure()){
			$https ='https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			return redirect()->secure( $https );
		}
        return $next($request);
    }
}
