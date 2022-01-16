@extends('layout')

@section('title', 'THÊM LỚP HỌC')

@section('content')

    <div class="col-md-4">
        <!-- general form elements -->
        <div class="card card-gray">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-uppercase">Thông tin lớp học</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('store_class') }}" method="post">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Tên lớp học</label>
                        @error('className')
                        <div class="danger text-red" style="float:right">{{ $message }}</div>
                        @enderror
                        <input type="text" class="form-control" id="name" name="className" autocomplete="off">
                    </div>
                    <div class="row">
                        <div class="col">
                            <!-- select -->
                            <div class="form-group">
                                <label>Khoa (Chuyên ngành)</label>
                                <select class="form-control" name="major" required>
                                    @if(!empty($major))
                                    @foreach($major as $each)
                                        <option value="{{ $each->id }}">{{ $each->name }}</option>
                                    @endforeach
                                    @else
                                        <option value="" selected>Chưa tồn tại khoa</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <!-- select -->
                            <div class="form-group">
                                <label>Niên khóa</label>
                                <select class="form-control" name="schoolyear" required>
                                    @if(!empty($schoolyear))
                                    @foreach($schoolyear as $each)
                                        <option value="{{ $each->id }}">{{ $each->codename }}</option>
                                    @endforeach
                                    @else
                                        <option value="" selected>Chưa tồn tại niên khóa</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <input type="submit" class="btn btn-success" value="Hoàn tất">
                    <a href="{{ route('class') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>

@endsection
