<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    public function loginView(Request $request)
    {
        if ($request->session()->has('id') && $request->session()->has('name')) {
            return redirect()->route('index');
        } else {
            return view('login');
        }
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'bail|required|min:4',
            'password' => 'required'
        ], [
            'username.required' => 'Tên đăng nhập không được bỏ trống.',
            'username.min' => 'Tên đăng nhập phải chứa ít nhất 4 kí tự.',
            'password.required' => 'Mật khẩu không được bỏ trống.'
        ]);

        $username = $request->get('username');
        $password = md5($request->get('password'));

        $user = Login::authenticate($username, $password); //calling query from Model: AdminAuthenticate

        if (count($user) == 1) {
            $request->session()->put('id', $user[0]->id);
            $request->session()->put('name', $user[0]->name);
            $request->session()->put('role', $user[0]->role);
            switch ($user[0]->role) {
                case 0:
                    return redirect()->route('index');
                    break;
                case 1:
                    return redirect()->route('admin');
                    break;
            }
        } else {
            return redirect()->route('login')->withErrors(['msg' => 'Tài khoản không tồn tại.']);
        }
    }

    public function logOut(Request $request)
    {
        $request->session()->flush();

        return redirect()->route('login');
    }
}
