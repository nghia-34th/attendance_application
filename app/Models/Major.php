<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Major extends Model
{
    use HasFactory;

    public static function index()
    {
        $data = DB::select("SELECT *, DATE_FORMAT(created_at,'%d/%m/%Y') as cre_date FROM `major` ORDER BY created_at DESC");

        return $data;
    }

    public function create()
    {
        DB::insert("INSERT INTO major(name, codename) VALUES ('$this->name', '$this->codeName') ");
    }

    public function store()
    {
        $subjectID = uniqid();

        DB::insert("INSERT INTO major (
                `id`,
                `name`,
                `codename`,
                `created_by`
                )
                VALUES (
                    '$subjectID',
                    '$this->majorName',
                    '$this->codeName',
                    '$this->createdBy'
                )");
    }

    public static function show($id)
    {
        $data = DB::select("SELECT *, DATE_FORMAT(created_at,'%d/%m/%Y') as cre_date FROM major WHERE id = ?", [$id]);
        return $data;
    }

    public function updates()
    {
        DB::update("UPDATE major SET name = ?, codename = ? WHERE id = ?", [$this->majorName, $this->codeName, $this->majorID]);
    }

    public function delete()
    {
        DB::delete("DELETE FROM major WHERE id = ?", [$this->majorID]);
    }
}
