<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Login extends Model
{
    use HasFactory;

    public static function authenticate($username, $password) {
        $user = DB::select('SELECT * FROM lecturer WHERE username = ? AND password = ?',[$username, $password]);

        return $user;
    }
}
