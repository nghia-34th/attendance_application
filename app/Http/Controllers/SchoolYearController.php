<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    public function index()
    {
        //Trả về view
        $data = SchoolYear::index();
        return view('schoolyear/schoolyear', compact('data'));
    }

    public function create()
    {
        return view('schoolyear/newSchoolyear');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codeName' => 'bail|required|max:20',
            'start' => 'required',
            'end' => 'required'
        ]);

        $schoolyear = new SchoolYear;

        $schoolyear->codeName = $request->codeName;
        $schoolyear->start = $request->start;
        $schoolyear->end = $request->end;
        $schoolyear->createdBy = $request->session()->get('name');

        $schoolyear->store();

        return redirect()->route("schoolyear");
    }

    public function detail(Request $request) {
        $id = $request->id;                     //display data from id
        $data = SchoolYear::show($id);

        return view('schoolyear/detailSchoolyear',compact('data'));
    }

    public function edit(Request $request) {
        $id = $request->id;                     //display data from id
        $data = SchoolYear::show($id);

        return view('schoolyear/editSchoolyear',compact('data'));
    }

    public function updates(Request $request) {
        $request->validate([
            'codeName' => 'bail|required|max:20',
            'start' => 'required',
            'end' => 'required'
        ]);

        $schoolyear = new SchoolYear();

        $schoolyear->schoolyearID = $request->route('id');
        $schoolyear->codeName = $request->codeName;
        $schoolyear->start = $request->start;
        $schoolyear->end = $request->end;

        $schoolyear->updates();

        return redirect()->route('schoolyear');
    }

    public function delete(Request $request) {
        $schoolyear = new SchoolYear();

        $schoolyear->schoolyearID = $request->route('id');

        $schoolyear->delete();

        return redirect()->route('schoolyear');
    }
}
