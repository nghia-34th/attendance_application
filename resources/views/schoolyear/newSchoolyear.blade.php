@extends('layout')

@section('title', 'THÊM NIÊN KHÓA')

@section('content')

    <div class="col-md-4">
        <!-- general form elements -->
        <div class="card card-gray">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-uppercase">Thông tin niên khóa</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('store_schoolyear') }}" method="post">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Mã niên khóa</label>
                        @error('codeName')
                        <div class="danger text-red" style="float:right">{{ $message }}</div>
                        @enderror
                        <input type="text" class="form-control" id="name" name="codeName" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="start">Bắt đầu</label>
                        @error('start')
                        <div class="danger text-red" style="float:right">{{ $message }}</div>
                        @enderror
                        <input type="number" min="1900" max="2099" step="1" class="form-control" id="start" name="start" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="end">Kết thúc</label>
                        @error('end')
                        <div class="danger text-red" style="float:right">{{ $message }}</div>
                        @enderror
                        <input type="number" min="1900" max="2099" step="1" class="form-control" id="end" name="end" autocomplete="off">
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <input type="submit" class="btn btn-success" value="Hoàn tất">
                    <a href="{{ route('schoolyear') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(function() {
            $( "#datepicker" ).datepicker({dateFormat: 'yy'});
        });
    </script>

@endsection
