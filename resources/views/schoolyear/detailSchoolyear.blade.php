@extends('layout')

@section('title', 'CHI TIẾT NIÊN KHÓA')

@section('content')

    <div class="col-md-4">
        <!-- general form elements -->
        <div class="card card-gray">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-uppercase">Thông tin niên khóa</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Mã niên khóa</label>
                    <input type="text" class="form-control" id="name" name="codeName" value="{{ $data[0]->codename }}" disabled>
                </div>
                <div class="form-group">
                    <label for="end">Bắt đầu</label>
                    <input type="number" min="1900" max="2099" step="1" class="form-control" id="start" name="start" value="{{ $data[0]->start }}" autocomplete="off" disabled>
                </div>
                <div class="form-group">
                    <label for="end">Kết thúc</label>
                    <input type="number" min="1900" max="2099" step="1" class="form-control" id="end" name="end" value="{{ $data[0]->end }}" autocomplete="off" disabled>
                </div>
                <div class="row">
                    <p>Ngày tạo niên khóa: {{ $data[0]->cre_date }}</p>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <a href="{{ route('edit_schoolyear',['id' => $data[0]->id]) }}" class="btn btn-primary">Sửa</a>
                <a href="{{ route('delete_schoolyear',['id' => $data[0]->id]) }}" class="btn btn-danger">Xóa</a>
                <a href="{{ route('schoolyear') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>

@endsection
