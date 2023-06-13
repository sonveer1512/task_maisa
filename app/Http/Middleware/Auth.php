<?php

namespace App\Http\Middleware;

use App\Models\RegisterModel;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class Auth
{
    public function handle(Request $request, Closure $next)
    {
        $email = Cookie::get('user_email');
        $password = Cookie::get('user_password');

        if (!empty($email) && !empty($password)) {
            $check = RegisterModel::where('email', $email)->first();
            if (!empty($check)) {
                $data = RegisterModel::where('email', $email)->where('flag', '1')->first();
                if (!empty($data)) {
                    if ($password == $data->password) {
                        Cookie::queue('user_email', $email, time() + 60 * 60 * 24 * 100);
                        Cookie::queue('user_password', $password, time() + 60 * 60 * 24 * 100);
                        $session['user'] = ['id' => $data->id, 'name' => $data->name, 'email' => $data->email];
                        Session::put($session);
                    }
                    else{
                        Session::flash('msg','Password is Updated,Login Again');
                        return redirect('/');
                    }
                }else{
                    Session::flash('msg','Accont Deactivate');
                    return redirect('/');
                }
            }
            else{
                    Session::flash('msg','Email Not Found');
                    return redirect('/');
            }
        }
        else{
            return redirect('/');
        }

        return $next($request);
    }
}
