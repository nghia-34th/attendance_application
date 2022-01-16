<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Lesson;
use DB;
use Carbon\Carbon;

class StudentDao extends Model
{
    use HasFactory;
    public $id;
    public $name;
    public $birthdate;
    public $class_id;
    public $absents;
    public $permission;

    public static function findByCourseId($courseId, $lessonDate = null)
    {
        $result = array();

        $absents = array();
        $permissions = array();
        $currentStatuses = array();
        // Array reasons chứa lí do nghỉ của một học sinh trong buổi học ngày học = $lessonDate
        $reasons = array();
        if ($lessonDate == null) {
            $lessonDate = Carbon::now()->toDateString();
        }
        self::getAbsentQuantity($courseId, $lessonDate, $absents, $permissions, $currentStatuses, $reasons);

        $course = Course::findById($courseId);
        if (!isset($course) || count($course) == 0) {
            return;
        }
        $students = Student::findByClassId($course[0]->class_id);
        if (!isset($students)) {
            return $result;
        }
        // Chuẩn bị dữ liệu
        foreach ($students as $student) {
            $newStudent = new StudentDao();
            $newStudent->id = $student->id;
            $newStudent->name = $student->name;
            $newStudent->birthdate = $student->birthdate;
            $newStudent->class_id = $student->class_id;
            $newStudent->absents = isset($absents[$newStudent->id]) ? $absents[$newStudent->id] : 0;
            $newStudent->permission = isset($permissions[$newStudent->id]) ? $permissions[$newStudent->id] : 0;
            $newStudent->currentStatus = isset($currentStatuses[$newStudent->id]) ? $currentStatuses[$newStudent->id] : "";
            $newStudent->absentReason = isset($reasons[$newStudent->id]) ? $reasons[$newStudent->id] : "";
            array_push($result, $newStudent);
        }
        return $result;
    }

    // Lấy số lượng buổi nghỉ/phép
    private static function getAbsentQuantity($courseId, $lessonDate, &$absents, &$permissions, &$currentStatuses, &$reasons)
    {
        // Check nếu buổi học đã tồn tại:
        // -> currentStatus chứa trạng thái đi học của từng sinhh viên
        // -> reasons chứa lí do nghỉ của từng sv
        if (app('App\Http\Controllers\LessonController')->lessonIsExist($lessonDate, $courseId)) {
            // Tìm lesson
            $lesson = Lesson::findByDateAndCourseId($lessonDate, $courseId)[0];
            // Lấy bản ghi về trạng thái đi học của sv trong buổi học vừa tìm được
            $attendances = Attendance::findByLessonId($lesson->id);

            foreach ($attendances as $attendance) {
                // Set trạng thái đi học của sinh viên trong buổi học vào mảng, ko có thì ko xét
                //   xử lí null ở dòng ~54
                // Kết quả buối cùng sẽ dưới dạng: $currentStatuses = ("61basdjbda" => "with reason", "51aj7nn4ba" => "late");
                $currentStatuses[$attendance->student_id] = $attendance->status;
                // Tương tự như trên nhưng là về lý do nghỉ
                // Kết quả buối cùng sẽ dưới dạng: $currentStatuses = ("61basdjbda" => "Người nhà mất", "51aj7nn4ba" => "Ngã xe nên đi muộn");
                $reasons[$attendance->student_id] = $attendance->absent_reason;
            }
        }

        $lessons = Lesson::findByCourseId($courseId);
        // Đếm số buổi nghỉ/phép của sinh viên dựa theo các bản ghi attendance
        foreach ($lessons as $lesson) {
            $attends = Attendance::findByLessonId($lesson->id);
            if (!isset($attends)) {
                return;
            }
            foreach ($attends as $attend) {
                if (!isset($absents[$attend->student_id])) {
                    $absents[$attend->student_id] = 0;
                }
                if (!isset($permissions[$attend->student_id])) {
                    $permissions[$attend->student_id] = 0;
                }
                if ($attend->status == 'without reason') {
                    $absents[$attend->student_id] += 1;
                } else if ($attend->status == 'late') {
                    $absents[$attend->student_id] += 0.3;
                    // Xử lí 0.9 -> 1
                    if ($absents[$attend->student_id] * 10 % 10 == 9) {
                        $absents[$attend->student_id] += 0.1;
                    }
                } else {
                    $permissions[$attend->student_id] += 1;
                }
            }
        }
    }
}