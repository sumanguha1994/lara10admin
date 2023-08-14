<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Exception;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class RoleCheck
{
    public function handle(Request $request, Closure $next, $role)
    {
        if(Auth::id()){
            $loginrole = Auth::user()->roles;
            if(strtolower($loginrole->role) === $role){
                return $next($request);
            }
            return redirect()->route('/');
        }else{
            return redirect()->route('login');
        }
    }
}
