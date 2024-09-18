<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Helper;
use DB;

class User
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
        if (!auth()->check())
        {
            return redirect()->route('login');
        }

        $user_meta = DB::table('wp_usermeta')->where('user_id', auth()->user()->ID)->where('meta_key', 'wp_capabilities')->whereNotNull('meta_value')->first();

        if (empty($user_meta) || empty($user_meta->meta_value))
        {
            auth()->logout();
            return redirect()->route('login');
        }

        $roles = @unserialize($user_meta->meta_value);

        if (empty($roles) || (!isset($roles['um_loan-administrator']) && !isset($roles['administrator'])))
        {
            auth()->logout();
            return redirect()->route('login');
        }

        return $next($request);
    }
}
