@extends('layout')

@section('title', 'CHI TIẾT MÔN HỌC')

@section('content')

    <div class="col-md-4">
        <!-- general form elements -->
        <div class="card card-gray">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-uppercase">Thông tin môn học</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Tên môn học</label>
                    <input type="text" class="form-control" id="name" name="subjectName" value="{{ $data[0]->name }}" disabled>
                </div>
                <div class="form-group">
                    <label for="credit_hours">Thời lượng môn(dự kiến)</label>
                    <input type="text" class="form-control" id="credit_hours" name="totalHours" value="{{ $data[0]->total_hours }}" disabled>
                </div>
                <div class="row">
                    <p>Ngày tạo môn học: {{ $data[0]->cre_date }}</p>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <a href="{{ route('edit_subject',['id' => $data[0]->id]) }}" class="btn btn-primary">Sửa</a>
                <a href="{{ route('delete_subject',['id' => $data[0]->id]) }}" class="btn btn-danger">Xóa</a>
                <a href="{{ route('subject') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>

@endsection
