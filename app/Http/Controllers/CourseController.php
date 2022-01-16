<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CourseController extends Controller
{
    //
    public function init()
    {
        $courses = app('App\Models\Course')->findAllAvailable(Session::get('id'));
        return view('attendance.index', compact('courses'));
    }

    public function findAll()

    {
        return Course::findAll();
    }

    public function findById($id)
    {
        return Course::findById($id);
    }

    public function index()
    {

        $data = Course::index();

        return view('course/course', compact('data'));
    }
    public function create()
    {
        $class = Course::classData();
        $subject = Course::subjectData();       //display data for <option></option>
        $lecturer = Course::lecturerData();

        return view('course/newCourse', compact('class', 'subject', 'lecturer'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'courseName' => 'bail|required|max:50',
            'creditHours' => 'bail|required|numeric'
        ]);

        $course = new Course();

        $course->courseName = $request->courseName;
        $course->creditHours = $request->creditHours;
        $course->class = $request->class;
        $course->subject = $request->subject;
        $course->lecturer = $request->lecturer;
        $course->type = $request->type;
        $course->createdBy = $request->session()->get('name');

        $course->store();

        return redirect()->route('course');
    }

    public function detail(Request $request)
    {
        $id = $request->id;                     //display data from id
        $data = Course::show($id);

        return view('course/detailCourse', compact('data'));
    }

    public function edit(Request $request)
    {
        $class = Course::classData();
        $subject = Course::subjectData();       //display data for <option></option>
        $lecturer = Course::lecturerData();

        $id = $request->id;                     //display data from id
        $data = Course::show($id);

        return view('course/editCourse', compact('data', 'class', 'subject', 'lecturer'));
    }

    public function updates(Request $request)
    {
        $request->validate([
            'courseName' => 'required|max:50',
            'creditHours' => 'bail|required|numeric'
        ]);

        $course = new Course();

        $course->courseId = $request->route('id');
        $course->courseName = $request->courseName;
        $course->creditHours = $request->creditHours;
        $course->class = $request->class;
        $course->subject = $request->subject;
        $course->lecturer = $request->lecturer;
        $course->type = $request->type;

        $course->updates();

        return redirect()->route('course');
    }

    public function delete(Request $request)
    {
        $course = new Course();

        $course->courseId = $request->route('id');

        $course->delete();

        return redirect()->route('course');
    }

    public function updateFinishedTime($courseId, $duration, $isNewLesson)
    {
        Course::updateFinishedTime($courseId, $duration, $isNewLesson);
    }

    // Tìm mọi khóa học thuốc quyền giảng viên đang đăng nhập
    public function findAllAvailable($lecturerId)
    {
        return app('App\Models\Course')->findAllAvailable($lecturerId);
    }
}
