<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Subject extends Model
{
    use HasFactory;
    public $id;
    public $name;
    public $creditHour;

    public static function index()
    {
        $data = DB::select("SELECT *, DATE_FORMAT(created_at,'%d/%m/%Y') as cre_date FROM `subject` ORDER BY created_at DESC");

        return $data;
    }

    public function store()
    {
        $subjectID = uniqid();

        DB::insert("INSERT INTO subject (
                `id`,
                `name`,
                `total_hours`,
                `created_by`
                )
                VALUES (
                    '$subjectID',
                    '$this->subjectName',
                    '$this->totalHours',
                    '$this->createdBy'
                )");
    }

    public static function show($id)
    {
        $data = DB::select("SELECT *, DATE_FORMAT(created_at,'%d/%m/%Y') as cre_date FROM subject WHERE id = ?", [$id]);
        return $data;
    }

    public function updates()
    {
        DB::update("UPDATE subject SET name = ?, total_hours = ? WHERE id = ?", [$this->subjectName, $this->totalHours, $this->subjectID]);
    }

    public function delete()
    {
        DB::delete("DELETE FROM subject WHERE id = ?", [$this->subjectID]);
    }
}
