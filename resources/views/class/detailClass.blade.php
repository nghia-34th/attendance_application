@extends('layout')

@section('title', $data[0]->name)

@section('content')

    <div class="col-md-4">
        <!-- general form elements -->
        <div class="card card-gray">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-uppercase">Thông tin lớp học</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Tên lớp học</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $data[0]->name }}" disabled>
                </div>
                <div class="form-group">
                    <label for="name">Sĩ số</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $data[0]->quantity }}" disabled>
                </div>
                <div class="row">
                    <div class="col">
                        <!-- select -->
                        <div class="form-group">
                            <label>Khoa (Chuyên ngành)</label>
                            <select class="form-control" name="major" disabled>
                                <option selected>{{ $data[0]->major }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <!-- select -->
                        <div class="form-group">
                            <label>Niên khóa</label>
                            <select class="form-control" name="schoolyear" disabled>
                                <option selected>{{ $data[0]->school_year }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <p>Ngày tạo lớp học: {{ $data[0]->cre_date }}</p>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <a href="{{ route('edit_class',['id' => $data[0]->id]) }}" class="btn btn-primary">Sửa</a>
                <a href="{{ route('delete_class',['id' => $data[0]->id]) }}" class="btn btn-danger">Xóa</a>
                <a href="{{ route('class') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>

@endsection
