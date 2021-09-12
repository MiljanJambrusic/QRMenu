<?php

namespace App\Http\Middleware;

use App\Helper\Helper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class EnsureUserRoleIsAllowedToAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /**Roles
            1. sadmin - super admin has access to all pages
            2. admin - all pages except for creating users
            3. konobar - only dashboard and orders
        */
        $userRole = auth()->user()->role;
        $currentRouteName = Route::currentRouteName();
        $helper = new Helper();
        $accessRolesArray = $helper->userAccessRole();
        if(in_array($currentRouteName,$accessRolesArray[$userRole])) {
            return $next($request);
        }
        else{
            abort(403,'Pristup stranici vam nije dozvoljen.');
        }
    }

}
