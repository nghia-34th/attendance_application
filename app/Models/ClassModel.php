<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ClassModel extends Model
{
    use HasFactory;

    public static function findById($id)
    {
        return DB::select('select * from class where id = ?', [$id]);
    }

    public static function index()
    {
        $data = DB::select(
            "SELECT
                class.id AS id,
                class.name AS name,
                major.name AS major,
                school_year.codename AS school_year,
                DATE_FORMAT(class.created_at, '%d/%m/%Y') AS cre_date
            FROM `class`
            LEFT JOIN major ON class.major_id = major.id
            LEFT JOIN school_year ON class.school_year_id = school_year.id
            ORDER BY class.created_at DESC"
        );

        return $data;
    }

    public static function majorData()
    {
        $major = DB::select("SELECT * FROM major");
        return $major;
    }

    public static function schoolyearData()
    {
        $school_year = DB::select("SELECT * FROM school_year");
        return $school_year;
    }

    public function store()
    {
        $classId = uniqid();

        DB::insert(
            "INSERT INTO class (
                `id`,
                `name`,
                `quantity`,
                `major_id`,
                `school_year_id`,
                `created_by`
                )
                VALUES (
                    '$classId',
                    '$this->className',
                    '0',
                    '$this->major',
                    '$this->schoolyear',
                    '$this->createdBy'
                )"
        );
    }

    public static function show($id)
    {
        $data = DB::select(
            "SELECT
                class.id AS id,
                class.name AS name,
                COUNT(student.id) AS quantity,
                major.name AS major,
                school_year.codename AS school_year,
                DATE_FORMAT(class.created_at,'%d/%m/%Y') as cre_date
            FROM `class`
            JOIN student ON class.id = student.class_id
            JOIN major ON class.major_id = major.id
            JOIN school_year ON class.school_year_id = school_year.id
            WHERE class.id = '$id'"
        );

        return $data;
    }

    public function updates()
    {
        DB::update(
            "UPDATE `class`
            SET
                `name` = '$this->className',
                `major_id` = '$this->major',
                `school_year_id` = '$this->schoolyear'
            WHERE id = '$this->classId'"
        );
    }

    public function delete()
    {
        DB::delete("DELETE FROM class WHERE id = '$this->classId'");
    }
}
