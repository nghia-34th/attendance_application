<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\StudentController;
use Redirect;


class AttendanceController extends Controller
{
    public function create(Request $request)
    {

        // dump($request);
        // Check buổi học đã tồn tại theo ngày tạo
        if (app('App\Http\Controllers\LessonController')->lessonIsExist($request->{'lesson-date'}, $request->{'current-course-id'})) {
            $lessonId = app('App\Http\Controllers\LessonController')->updateLesson($request);
            // Xóa các thông tin điểm danh sẵn có
            Attendance::deleteByLessonId($lessonId);
        } else {
            dump("ok");
            $lessonId = app('App\Http\Controllers\LessonController')->create($request);
        }

        // Tạo (tạo lại) các bản ghi điểm danh
        $data = $request->{'students'};
        foreach ($data as $student) {
            if (!is_null($student["status"])) {
                $attendance = new Attendance();
                $attendance->studentId = $student['student_id'];
                $attendance->status = $student['status'];
                $attendance->absentReason = $student['absent_reason'];
                $attendance->lessonId = $lessonId;
                $attendance->create();
            }
        }

        // return redirect('/index');
        // Thêm request param để StudentController->StudentList lấy
        $request->request->add(['course-id' => $request->{'current-course-id'}]);
        return StudentController::StudentList($request)->with('alert','hello');;
    }
}
