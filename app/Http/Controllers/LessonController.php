<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Lesson;
use App\CourseController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class LessonController extends Controller
{
    public function create(Request $request)
    {
        // Tạo mới buổi học
        $lesson = new Lesson();
        $newLessonId = uniqid();
        $lesson->id = $newLessonId;
        $lesson->start = $request->start['hour'] . ":" . $request->start['minutes'];
        $lesson->end = $request->end['hour'] . ":" . $request->end['minutes'];
        $lesson->note = $request->note;
        $lesson->lecturerId = session('id');
        $lesson->courseId = $request->{'current-course-id'};
        $lesson->createdAt = $request->{'lesson-date'};
        $lesson->createdBy = session('name');
        $lesson->create();

        self::lessonDurationHandler($lesson->start, $lesson->end, $lesson->courseId);

        return $newLessonId;
    }

    public function updateLesson(Request $request)
    {
        $updatedLesson = Lesson::findByDateAndCourseId($request->{'lesson-date'}, $request->{'current-course-id'})[0];

        $lesson = new Lesson();
        $lesson->id = $updatedLesson->id;
        $lesson->start = $request->start['hour'] . ":" . $request->start['minutes'];
        $lesson->end = $request->end['hour'] . ":" . $request->end['minutes'];
        $lesson->note = $request->note;
        $lesson->courseId = $request->{'current-course-id'};
        $lesson->updateLesson($lesson);

        self::lessonDurationHandler(
            $lesson->start,
            $lesson->end,
            $lesson->courseId,
            $updatedLesson->start,
            $updatedLesson->end
        );

        return $lesson->id;
    }

    public static function lessonIsExist($date, $courseId)
    {
        // dump(Lesson::findByDateAndCourseId($date, $courseId));

        if (Lesson::findByDateAndCourseId($date, $courseId) != null) {
            return true;
        } else {
            return false;
        }
    }

    // Cập nhật số buổi và số giờ đã dạy của Khóa học(Course)
    private function lessonDurationHandler($start, $end, $courseId, $prevStart = null, $prevEnd = null)
    {
        $lessonDuration = strtotime($start) - strtotime($end);
        // Làm tròn đến góc phần tư gần nhất (0.0, 0.25, 0.5, 0.75)
        $lessonDuration = floor(round(abs($lessonDuration) / 3600, 2) * 4) / 4;

        // Nếu buổi học đã tồn tại:
        // - xử lí khác biệt về thời lượng
        // - update thời gian đã học của khóa học nhưng ko tăng buổi học
        if ($prevStart != null && $prevEnd != null) {
            $prevLessonDuration = strtotime($prevEnd) - strtotime($prevStart);
            $prevLessonDuration = floor(round(abs($prevLessonDuration) / 3600, 2) * 4) / 4;
            $newDuration = $lessonDuration - $prevLessonDuration;

            //Cập nhật và số giờ đã dạy mà ko làm tăng số buổi đã học
            app('App\Http\Controllers\CourseController')->updateFinishedTime($courseId, $newDuration, false);
        } else {
            //Cập nhật số buổi và số giờ đã dạy
            app('App\Http\Controllers\CourseController')->updateFinishedTime($courseId, $lessonDuration, true);
        }
    }
}