<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use Redirect;
use DB;

class SubjectController extends Controller
{
    public function index()
    {
        //Trả về view
        $data = Subject::index();
        return view('subject/subject', compact('data'));
    }

    public function create()
    {
        return view('subject/newSubject');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subjectName' => 'bail|required|max:20',
            'totalHours' => 'bail|required|numeric'
        ]);

        $subject = new Subject;

        $subject->subjectName = $request->subjectName;
        $subject->totalHours = $request->totalHours;
        $subject->createdBy = $request->session()->get('name');

        $subject->store();

        return redirect()->route("subject");
    }

    public function detail(Request $request) {
        $id = $request->id;                     //display data from id
        $data = Subject::show($id);

        return view('subject/detailSubject',compact('data'));
    }

    public function edit(Request $request) {
        $id = $request->id;                     //display data from id
        $data = Subject::show($id);

        return view('subject/editSubject',compact('data'));
    }

    public function updates(Request $request) {
        $request->validate([
            'subjectName' => 'bail|required|max:20',
            'totalHours' => 'bail|required|numeric'
        ]);

        $subject = new Subject();

        $subject->subjectID = $request->route('id');
        $subject->subjectName = $request->subjectName;
        $subject->totalHours = $request->totalHours;

        $subject->updates();

        return redirect()->route('subject');
    }

    public function delete(Request $request) {
        $subject = new Subject();

        $subject->subjectID = $request->route('id');

        $subject->delete();

        return redirect()->route('subject');
    }
}
