@extends('layout')

@section('title', 'CHI TIẾT CHUYÊN NGÀNH')

@section('content')

    <div class="col-md-4">
        <!-- general form elements -->
        <div class="card card-gray">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-uppercase">Thông tin khoa</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Tên khoa</label>
                    <input type="text" class="form-control" id="name" name="majorName" value="{{ $data[0]->name }}" disabled>
                </div>
                <div class="form-group">
                    <label for="credit_hours">Mã khoa</label>
                    <input type="text" class="form-control" id="credit_hours" name="codeName" value="{{ $data[0]->codename }}" disabled>
                </div>
                <div class="row">
                    <p>Ngày tạo khoa: {{ $data[0]->cre_date }}</p>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <a href="{{ route('edit_major',['id' => $data[0]->id]) }}" class="btn btn-primary">Sửa</a>
                <a href="{{ route('delete_major',['id' => $data[0]->id]) }}" class="btn btn-danger">Xóa</a>
                <a href="{{ route('major') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>

@endsection
