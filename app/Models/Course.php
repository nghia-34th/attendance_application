<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Course extends Model
{
    use HasFactory;
    public $subject;
    public $id;
    public $name;
    public $credit_hours;

    public static function findAll()
    {
        return DB::select("select * from course");
    }

    public static function findById($id)
    {
        return DB::select("select * from course where id = ?", [$id]);
    }

    public static function updateFinishedTime($id, $lessonDuration, $isNewLesson)
    {
        // Là buổi học mới thì +1 vào số buổi dã dạy, không thì thôi
        if ($isNewLesson) {
            $query = "
            UPDATE course
            SET
                finished_hour = finished_hour + ($lessonDuration),
                finished_lesson = CASE
                    WHEN (finished_lesson IS NULL) THEN 1
                    ELSE finished_lesson + 1
                    END
            WHERE `id` = '$id'
            ";
        } else {
            $query = "
            UPDATE course
            SET
                finished_hour = finished_hour + ($lessonDuration)
            WHERE `id` = '$id'
            ";
        }
        DB::update($query);
    }

    public static function index()
    {
        $data = DB::select(
            "SELECT
                course.id AS id,
                course.name AS course_name,
                class.name AS class,
                subject.name AS subject,
                FLOOR(course.credit_hours) AS credit_hours,
                lecturer.name AS lecturer,
                DATE_FORMAT(course.created_at,'%d/%m/%Y') as cre_date
            FROM `course`
            JOIN class ON course.class_id = class.id
            JOIN SUBJECT ON course.subject_id = SUBJECT.id
            LEFT JOIN lecturer_course_rel ON course.id = lecturer_course_rel.course_id
            LEFT JOIN lecturer ON lecturer_course_rel.lecturer_id = lecturer.id
            ORDER BY course.created_at DESC"
        );

        return $data;
    }

    /*start of CourseController::create()*/
    public static function classData()
    {
        $class = DB::select("SELECT * FROM class");
        return $class;
    }

    public static function subjectData()
    {
        $subject = DB::select("SELECT * FROM subject");
        return $subject;
    }

    public static function lecturerData()
    {
        $lecturer = DB::select("SELECT * FROM lecturer WHERE role = 0"); //non-admin
        return $lecturer;
    }
    /*end of CourseController::create()*/

    public function store()
    {
        $courseId = uniqid();   //course id
        $lcrId = uniqid();      //lecturer_course_rel id

        DB::insert(
            "INSERT INTO course (
                `id`,
                `name`,
                `credit_hours`,
                `class_id`,
                `subject_id`,
                `created_by`
                )
                VALUES (
                    '$courseId',
                    '$this->courseName',
                    '$this->creditHours',
                    '$this->class',
                    '$this->subject',
                    '$this->createdBy'
                )"
        );
        DB::insert(
            "INSERT INTO lecturer_course_rel(
                `id`,
                `type`,
                `lecturer_id`,
                `course_id`,
                `created_by`
                        )
            VALUES(
                '$lcrId',
                '$this->type',
                '$this->lecturer',
                '$courseId',
                '$this->createdBy'
            )"
        );
    }

    public static function show($id)
    {
        $data = DB::select(
            "SELECT
                course.id AS id,
                course.name AS course,
                FLOOR(course.credit_hours) AS credit_hours,
                class.name AS class,
                subject.name AS subject,
                lecturer.name AS lecturer,
                lecturer_course_rel.id AS lcr_id,
                lecturer_course_rel.type AS type,
                DATE_FORMAT(course.created_at,'%d/%m/%Y') as cre_date
            FROM
                course
            JOIN class ON course.class_id = class.id
            JOIN subject ON course.subject_id = subject.id
            LEFT JOIN lecturer_course_rel ON course.id = lecturer_course_rel.course_id
            LEFT JOIN lecturer ON lecturer_course_rel.lecturer_id = lecturer.id
            WHERE course.id = '$id'"
        );

        return $data;
    }

    public function updates()
    {
        DB::update(
            "UPDATE `course`
            SET
                `name` = '$this->courseName',
                `credit_hours` = '$this->creditHours',
                `class_id` = '$this->class',
                `subject_id` = '$this->subject'
            WHERE id = '$this->courseId'"
        );
        DB::update(
            "UPDATE `lecturer_course_rel`
            SET
                `type` = '$this->type',
                `lecturer_id` = '$this->lecturer'
            WHERE course_id = '$this->courseId'"
        );
    }

    public function delete()
    {
        DB::delete("DELETE FROM lecturer_course_rel WHERE course_id = '$this->courseId'");
        DB::delete("DELETE FROM course WHERE id = '$this->courseId'");
    }

    // Tìm mọi khóa học thuốc quyền giảng viên đang đăng nhập
    public function findAllAvailable($lecturerId)
    {
        return DB::select(
            "SELECT c.* FROM course c JOIN lecturer_course_rel lcr on c.id = lcr.course_id WHERE lcr.lecturer_id = ?",
            [$lecturerId]
        );
    }

    public static function checkCourse()
    {
        $data = [];

        $classes = DB::select("SELECT * FROM class");
        foreach ($classes as $class) {
            // Array chứa thông tin các môn lớp có thể học
            $subjectInfos = array();

            // Tìm kiếm các môn CHUYÊN NGÀNH của lớp thông qua bảng course_check
            $subjects = DB::select(
                "SELECT * FROM subject where id IN (SELECT subject_id from course_check WHERE major_id = ?)",
                [$class->major_id]
            );
            foreach ($subjects as $subject) {
                array_push($subjectInfos, array($subject->name, $subject->id));
            }

            // Các môn ĐẠI CƯƠNG
            $geSubjects = DB::select("SELECT * FROM subject where is_ge = 1");
            foreach ($geSubjects as $geSubject) {

                array_push($subjectInfos, array($geSubject->name, $geSubject->id));
            }
            // Thêm bản ghi chứa id lớp cùng thông tin các môn được học
            $data[$class->id] = $subjectInfos;
        }
        // Chuyển về JSON
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
