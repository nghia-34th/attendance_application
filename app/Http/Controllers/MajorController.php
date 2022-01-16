<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Major;

class MajorController extends Controller
{
    public function index()
    {
        //Trả về view
        $data = Major::index();
        return view('major/major', compact('data'));
    }

    public function create()
    {
        return view('major/newMajor');
    }

    public function store(Request $request)
    {
        $request->validate([
            'majorName' => 'bail|required|max:50',
            'codeName' => 'bail|required|max:20'
        ]);

        $subject = new Major;

        $subject->majorName = $request->majorName;
        $subject->codeName = $request->codeName;
        $subject->createdBy = $request->session()->get('name');

        $subject->store();

        return redirect()->route("major");
    }

    public function detail(Request $request) {
        $id = $request->id;                     //display data from id
        $data = Major::show($id);

        return view('major/detailMajor',compact('data'));
    }

    public function edit(Request $request) {
        $id = $request->id;                     //display data from id
        $data = Major::show($id);

        return view('major/editMajor',compact('data'));
    }

    public function updates(Request $request) {
        $request->validate([
            'majorName' => 'bail|required|max:50',
            'codeName' => 'bail|required|max:20'
        ]);

        $subject = new Major();

        $subject->majorID = $request->route('id');
        $subject->majorName = $request->majorName;
        $subject->codeName = $request->codeName;

        $subject->updates();

        return redirect()->route('major');
    }

    public function delete(Request $request) {
        $subject = new Major();

        $subject->majorID = $request->route('id');

        $subject->delete();

        return redirect()->route('major');
    }
}
