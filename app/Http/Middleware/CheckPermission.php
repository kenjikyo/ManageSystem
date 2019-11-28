<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\User;
class CheckPermission
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
        $user = session('user');
        $check_level = User::where('User_ID',$user->User_ID)->first();
        if ($check_level->User_Level == 1 || $check_level->User_Level == 2 || $check_level->User_Level == 3){
            return $next($request);
        }
        return redirect()->route('Dashboard');
    }
}
