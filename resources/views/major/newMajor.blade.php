@extends('layout')

@section('title', 'THÊM CHUYÊN NGÀNH')

@section('content')

    <div class="col-md-4">
        <!-- general form elements -->
        <div class="card card-gray">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-uppercase">Thông tin khoa</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('store_major') }}" method="post">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Tên khoa</label>
                        @error('majorName')
                        <div class="danger text-red" style="float:right">{{ $message }}</div>
                        @enderror
                        <input type="text" class="form-control" id="name" name="majorName" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="total_hours">Mã khoa</label>
                        @error('codeName')
                        <div class="danger text-red" style="float:right">{{ $message }}</div>
                        @enderror
                        <input type="text" class="form-control" id="total_hours" name="codeName" autocomplete="off">
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <input type="submit" class="btn btn-success" value="Hoàn tất">
                    <a href="{{ route('major') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>

@endsection
