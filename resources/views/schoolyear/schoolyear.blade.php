@extends('layout')

@section('title', 'NIÊN KHÓA')

@section('content')

    <table id="example2" class="table align-middle table-bordered">
        <thead>
        <tr class="bg-dark">
            <th class="fs-5 text-white text-center border-0">STT</th>
            <th class="fs-5 text-white border-0">Mã niên khóa</th>
            <th class="fs-5 text-white text-center border-0">Bắt đầu</th>
            <th class="fs-5 text-white text-center border-0">Kết thúc</th>
            <th class="fs-5 text-white text-center border-0">Ngày tạo</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($data as $each)
            <tr class="clickable-row" onclick="window.location='{{ route('schoolyear_detail',['id' => $each->id]) }}'">
                <td class="text-center border-0">{{ $loop->index + 1 }}</td>
                <td class="border-0">{{ $each->codename }}</td>
                <td class="text-center border-0">{{ $each->start }}</td>
                <td class="text-center border-0">{{ $each->end }}</td>
                <td class="text-center border-0">{{ $each->cre_date }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
