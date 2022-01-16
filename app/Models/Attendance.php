<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Attendance extends Model
{
    use HasFactory;
    public $id;
    public $status;
    public $absentReason;
    public $studentId;
    public $lessonId;
    public $createdBy;

    public function create()
    {
        $this->id = uniqid();
        DB::insert(
            "INSERT INTO attendance(id, status, absent_reason, student_id, lesson_id, created_by)
        VALUES(?, ?, ?, ?, ?, ?)",
            [$this->id, $this->status, $this->absentReason, $this->studentId, $this->lessonId, 'admin']
        );
    }

    public static function findByLessonId($lessonId)
    {
        return DB::select('select * from attendance where `lesson_id` = ?', [$lessonId]);
    }

    public static function deleteByLessonId($lessonId)
    {
        DB::delete('delete from attendance where `lesson_id` = ?', [$lessonId]);
    }
}
