<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LecturerAuthenticate
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
        if ($request->session()->has('id') && $request->session()->has('name')) {
            $role = $request->session()->get('role');
            switch ($role) {
                case 0:
                    return $next($request);
                    break;
                case 1:
                    return redirect()->route('admin');
                    break;
            }
        }
        abort(404);
    }
}
