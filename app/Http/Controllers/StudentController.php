<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentDao;
use App\Models\Lesson;
use App\Http\Controllers\CourseController;
use App\Models\ClassModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class StudentController extends Controller
{
    public static function StudentList(Request $request)
    {
        // Lấy lại danh sách các courses
        $courses = app('App\Http\Controllers\CourseController')->findAllAvailable($request->session()->get('id'));
        // $courses = app('App\Http\Controllers\CourseController')->findAll();
        // Tìm danh sách sinh viên theo course
        $courseId = $request->all()['course-id'];
        $list = StudentDao::findByCourseId($courseId);

        // Lấy thông tin về course được chọn
        $currentCourse = app('App\Http\Controllers\CourseController')->findById($courseId);
        if (!isset($currentCourse) || count($currentCourse) == 0) {
            return;
        }
        $currentCourse = $currentCourse[0];
        // Lấy tên lớp
        $className = ClassModel::findById($currentCourse->{'class_id'})[0]->name;

        // Lấy các buổi học đã dạy của khóa (course)
        $lessons = Lesson::findByCourseId($courseId);
        // Bỏ buổi học hôm nay khỏi lịch sử các buổi học
        foreach ($lessons as $lesson) {
            if (date('Y-m-d', strtotime($lesson->created_at)) == Carbon::now()->toDateString()) {
                array_pop($lessons);
            }
        }
        return view('attendance.index', compact('list', 'courses', 'currentCourse', 'className', 'lessons'));
    }

    public function getLessonAttendanceList($lessonId)
    {
        // Lấy lại danh sách các courses
        $courses = app('App\Http\Controllers\CourseController')->findAllAvailable(Session::get('id'));

        // Lấy các thông tin chung về lesson được chọn
        $lesson = app('App\Models\Lesson')->findById($lessonId);
        if (!isset($lesson)) {
            return;
        }
        $lesson = $lesson[0];
        $curLessonDate = date('d/m/Y', strtotime($lesson->created_at));
        $lessonStart['hour'] = explode(':', $lesson->start)[0];
        $lessonStart['minutes'] = explode(':', $lesson->start)[1];
        $lessonEnd['hour'] = explode(':', $lesson->end)[0];
        $lessonEnd['minutes'] = explode(':', $lesson->end)[1];

        // Lấy thông tin về course được chọn
        $currentCourse = app('App\Http\Controllers\CourseController')->findById($lesson->course_id);
        if (!isset($currentCourse)) {
            return;
        }
        $currentCourse = $currentCourse[0];

        // Lấy thông tin điểm danh sinh viên
        $list = StudentDao::findByCourseId($lesson->course_id, $lesson->created_at);

        // Lấy tên lớp
        $className = ClassModel::findById($currentCourse->{'class_id'})[0]->name;

        // Lấy các buổi học đã dạy của khóa (course)
        $lessons = Lesson::findByCourseId($lesson->course_id);
        // Bỏ buổi học hôm nay khỏi lịch sử các buổi học
        foreach ($lessons as $lesson) {
            if (date('Y-m-d', strtotime($lesson->created_at)) == Carbon::now()->toDateString()) {
                array_pop($lessons);
            }
        }
        return view('attendance.index', compact('list', 'courses', 'currentCourse', 'className', 'lessons', 'curLessonDate', 'lessonStart', 'lessonEnd'));
    }

    // public static function StudentList(Request $request)
    // {
    //     $courseId = $request->all()['course-id'];
    //     $list = StudentDao::findByCourseId($courseId);
    //     $courses = app('App\Http\Controllers\CourseController')->findAll();
    //     $currentCourse = app('App\Http\Controllers\CourseController')->findById($courseId);
    //     if (isset($currentCourse)) {
    //         $currentCourse = $currentCourse[0];
    //         $className = ClassModel::findById($currentCourse->{'class_id'})[0]->name;
    //         return view('attendance.index', compact('list', 'courses', 'currentCourse', 'className'));
    //     }
    //     return;
    // }

    // public function StudentList()
    // {
    //     $list = Student::StudentList();
    //     return view('index', compact('list'));
    // }
}