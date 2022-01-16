<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SchoolYear extends Model
{
    use HasFactory;

    public static function index()
    {
        $data = DB::select("SELECT *, DATE_FORMAT(created_at,'%d/%m/%Y') as cre_date FROM `school_year` ORDER BY created_at DESC");

        return $data;
    }

    public function store()
    {
        $schoolyearID = uniqid();

        DB::insert("INSERT INTO school_year (
                `id`,
                `codename`,
                `start`,
                `end`,
                `created_by`
                )
                VALUES (
                    '$schoolyearID',
                    '$this->codeName',
                    '$this->start',
                    '$this->end',
                    '$this->createdBy'
                )");
    }

    public static function show($id)
    {
        $data = DB::select("SELECT *, DATE_FORMAT(created_at,'%d/%m/%Y') as cre_date FROM school_year WHERE id = ?", [$id]);
        return $data;
    }

    public function updates()
    {
        DB::update("UPDATE school_year SET codename = ?, start = ?, end = ? WHERE id = ?", [$this->codeName, $this->start, $this->end, $this->schoolyearID]);
    }

    public function delete()
    {
        DB::delete("DELETE FROM school_year WHERE id = ?", [$this->schoolyearID]);
    }
}
