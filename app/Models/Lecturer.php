<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lecturer extends Model
{
    use HasFactory;

    public $table = 'lecturer';
    public $timestamps = false;

    public static function LecturerList() {
        $list = DB::select("SELECT * FROM lecturer");
        return $list;
    }

    public static function LecturerEdit($id) {
        $edit = DB::select("SELECT * FROM lecturer WHERE id = ?",[$id]);
        return $edit;
    }
}
