<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SolucionDigitalController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class System
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()) {
            if(setting('system.development') && !auth()->user()->hasRole('admin')){
                return redirect('development');
            }
        }

 
        $soliciondigital = new SolucionDigitalController();
        $data = $soliciondigital->settings_code();

        if (empty($data)) {
            return redirect()->route('errors', ['id'=>500]);
        }
        else
        {
            if($soliciondigital->payment_alert() == 'finalizado')
            {
                $blockedMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];
                $allowedRoutes = ['admin/login', 'admin/logout', 'admin/settings']; // Rutas permitidas
                if (in_array($request->method(), $blockedMethods) && !in_array($request->path(), $allowedRoutes)) {
                    return redirect()->back()
                    ->withInput()
                    ->with(['message' => 'Para continuar con el servicio sin interrupciones, contacte al administrador.', 'alert-type' => 'error']);
                }
            }
        }

        return $next($request);
    }
}
