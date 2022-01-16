<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lecturer;

class LecturerController extends Controller
{
    public function LecturerList() {
        $list = Lecturer::LecturerList();
        return view('lecturer',compact('list'));
    }

    public function LecturerEdit(Request $request) {
        $id = $request->id;
        $edit = Lecturer::LecturerEdit($id);

        return view('editLecturer',compact('edit'));
    }
}
