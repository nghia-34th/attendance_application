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
            <!-- form start -->
            <form action="{{ route('update_class',['id' => $data[0]->id]) }}" method="post">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Tên lớp học</label>
                        @error('className')
                        <div class="danger text-red" style="float:right">{{ $message }}</div>
                        @enderror
                        <input type="text" class="form-control" id="name" name="className" value="{{ $data[0]->name }}" autocomplete="off">
                    </div>
                    <div class="row">
                        <div class="col">
                            <!-- select -->
                            <div class="form-group">
                                <label>Khoa (Chuyên ngành)</label>
                                <select class="form-control" name="major">
                                    @foreach($major as $each)
                                        @if($each->name == $data[0]->major)
                                            <option value="{{ $each->id }}" selected>{{ $each->name }}</option>
                                        @else
                                            <option value="{{ $each->id }}">{{ $each->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <!-- select -->
                            <div class="form-group">
                                <label>Niên khóa</label>
                                <select class="form-control" name="schoolyear">
                                    @foreach($schoolyear as $each)
                                        @if($each->codename == $data[0]->school_year)
                                            <option value="{{ $each->id }}" selected>{{ $each->codename }}</option>
                                        @else
                                            <option value="{{ $each->id }}">{{ $each->codename }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <input type="submit" class="btn btn-success" value="Hoàn tất">
                    <a href="{{ route('class_detail',['id' => $data[0]->id]) }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>

@endsection
