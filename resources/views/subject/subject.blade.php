@extends('layout')

@section('title', 'MÔN HỌC')

@section('content')

    <table id="example2" class="table align-middle table-bordered">
        <thead>
        <tr class="bg-dark">
            <th class="fs-5 text-white text-center border-0">STT</th>
            <th class="fs-5 text-white border-0">Tên môn học</th>
            <th class="fs-5 text-white text-center border-0">Thời lượng môn</th>
            <th class="fs-5 text-white text-center border-0">Ngày tạo</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($data as $each)
            <tr class="clickable-row" onclick="window.location='{{ route('subject_detail',['id' => $each->id]) }}'">
                <td class="text-center border-0">{{ $loop->index + 1 }}</td>
                <td class="border-0">{{ $each->name }}</td>
                <td class="text-center border-0">{{ $each->total_hours }}</td>
                <td class="text-center border-0">{{ $each->cre_date }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
