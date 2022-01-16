<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use Illuminate\Http\Request;
use App\Models\LopModel;
use Illuminate\Support\Facades\DB;
use Redirect;

class ClassController extends Controller
{
    public function index()
    {
        $data = ClassModel::index();

        return view('class/class', compact('data'));
    }

    public function create()
    {
        $major = ClassModel::majorData();
        $schoolyear = ClassModel::schoolyearData();

        return view('class/newClass', compact('major', 'schoolyear'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'className' => 'bail|required|max:50'
        ]);

        $class = new ClassModel();

        $class->className = $request->className;
        $class->major = $request->major;
        $class->schoolyear = $request->schoolyear;
        $class->createdBy = $request->session()->get('name');

        $class->store();

        return redirect()->route('class');
    }

    public function detail(Request $request)
    {
        $id = $request->id;                     //display data from id
        $data = ClassModel::show($id);
        return view('class/detailClass', compact('data'));
    }

    public function edit(Request $request)
    {
        $major = ClassModel::majorData();
        $schoolyear = ClassModel::schoolyearData();

        $id = $request->id;                     //display data from id
        $data = ClassModel::show($id);

        return view('class/editClass', compact('data', 'major', 'schoolyear'));
    }

    public function updates(Request $request)
    {
        $request->validate([
            'className' => 'bail|required|max:50'
        ]);

        $class = new ClassModel();

        $class->classId = $request->route('id');
        $class->className = $request->className;
        $class->major = $request->major;
        $class->schoolyear = $request->schoolyear;

        $class->updates();

        return redirect()->route('class');
    }

    public function delete(Request $request)
    {
        $course = new ClassModel();

        $course->classId = $request->route('id');

        $course->delete();

        return redirect()->route('class');
    }
}
