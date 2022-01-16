@extends('layout')
@section('title', 'BKACAD - Điểm danh')
@section('css')
    <style>
        /* h2 {
                        margin: 16px;
                    }

                    .container {
                        margin-top: 100px;
                        padding: 32px;
                    } */

        .select-box {
            position: relative;
            display: flex;
            width: 100%;
            flex-direction: column;
        }

        .select-box .course-container {
            background: #2f3648;
            color: #f5f6fa;
            max-height: 0;
            width: 100%;
            opacity: 0;
            transition: all 0.4s;
            border-radius: 8px;
            overflow: hidden;

            order: 1;
        }

        .selected {
            background: #2f3640;
            border-radius: 8px;
            margin-bottom: 8px;
            color: #f5f6fa;
            position: relative;

            order: 0;

            transition: all 0.4s;
        }

        .selected::after {
            content: "";
            background: url("{{ asset('img/arrow-down.svg') }}");
            background-size: contain;
            background-repeat: no-repeat;

            position: absolute;
            height: 100%;
            width: 32px;
            right: 10px;
            top: 7px;
        }

        .select-box .course-container.active {
            max-height: 200px;
            opacity: 1;
            overflow-y: scroll;
            margin-top: 54px;
        }

        .select-box .course-container.active+.selected::after {
            transform: rotateX(180deg);
            top: -6px;
        }

        .select-box .course-container::-webkit-scrollbar {
            width: 8px;
            background: #0d141f;
            border-radius: 0 8px 8px 0;
        }

        .select-box .course-container::-webkit-scrollbar-thumb {
            background: #525861;
            border-radius: 0 8px 8px 0;
        }

        .selected {
            padding: 12px 24px;
            cursor: pointer;
        }

        .select-box .course:hover {
            background: #414b57;
        }

        .select-box label {
            cursor: pointer;
        }

        .select-box .course .radio {
            display: none;
        }

        .select-box .course .course-label {
            width: 100%;
            height: 100%;
            padding: 12px 24px;
            cursor: pointer;
        }

        .search-box input {
            width: 100%;
            padding: 5px 12px;
            font-size: 16px;
            position: absolute;
            border-radius: 8px;
            z-index: 100;
            border: 8px solid #2f3640;

            opacity: 0;
            pointer-events: none;
            transition: all 0.4s;
        }

        .search-box input:focus {
            outline: none;
        }

        .select-box .course-container.active~.search-box input {
            opacity: 1;
            pointer-events: auto;
        }

    </style>
    {{-- <link rel="stylesheet" href="../public/css/course_dropdown.css"> --}}
@endsection
{{-- @section('title', 'Điểm Danh') --}}
@section('content')
    <h2>Giảng viên: {{ session('name') }}</h2>
    {{-- Form chọn lớp --}}
    <form action="{{ route('course_attendance') }}" method="POST" class="form">
        @csrf
        <div class="select-box">
            <div class="course-container">
                @foreach ($courses as $course)
                    <div class="course">
                        <input type="radio" class="radio" id="<?php echo $course->id; ?>" name="course-id"
                            value="<?php echo $course->id; ?>" required>
                        <label for="<?php echo $course->id; ?>" class="course-label"><?php echo $course->name; ?></label>
                    </div>
                @endforeach
            </div>
            <div class="selected">
                CHỌN LỚP...
            </div>
            <div class="search-box">
                <input type="text" placeholder="Tìm kiếm...">
            </div>
        </div>

        <input type="submit" class="btn btn-outline-success fs-5 fw-bold fst-italic mt-2 mb-2 float-right"
            value="Lấy danh sách điểm danh" />
    </form>
    {{-- Thông tin chung --}}
    <span name="general-info" class="">
        @isset($currentCourse)
            <h4>Lớp: <?php echo isset($className) ? $className : 'Chưa có'; ?> </h4>
            <h4>Môn học: <?php echo isset($currentCourse->{'name'}) ? $currentCourse->{'name'} : 'Chưa có'; ?> </h4>
            <h4>Tổng số giờ: <?php echo isset($currentCourse) ? $currentCourse->{'credit_hours'} + 0 : 0; ?> </h4>
            <h4>
                Số giờ còn lại: <?php echo isset($currentCourse) ? $currentCourse->{'credit_hours'} - $currentCourse->{'finished_hour'} + 0 : 0; ?>
            </h4>
            <h4>Số buổi đã dạy: <?php echo isset($currentCourse->{'finished_lesson'}) ? $currentCourse->{'finished_lesson'} : '0'; ?></h4>
        @endisset
    </span>
    <br>

    {{-- Danh sách điểm danh --}}
    <div id="studentRecords">
        <form action="{{ route('create') }}" method="POST" onsubmit="return validateForm()" name="attendanceForm">
            @csrf
            {{-- Thông tin khóa học đang được chọn --}}
            @isset($currentCourse)
                <input type="hidden" name='current-course-id' value='<?php echo $currentCourse->id; ?>'>
            @endisset
            {{-- Danh sách điểm danh --}}
            <table id="example1" class="table table-striped align-middle table-bordered">
                <thead>
                    <tr class="bg-dark">
                        <th td class="text-center fs-5 text-white">STT</th>
                        <th class="fs-5 text-white">Tên sinh viên</th>
                        <th td class="text-center fs-5 text-white" colspan="4">Điểm danh</th>
                        <th class="fs-5 text-white">Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                @isset($list)
                    @foreach ($list as $each)
                        <tr>
                            <input type="hidden" name="students[{{ $loop->index + 1 }}][student_id]"
                                value="{{ $each->id }}" />
                            <td class="text-center">{{ $loop->index + 1 }}</td>
                            <td class="">
                                <span class="roll fw-bolder"><a href="#">{{ $each->name }}</a></span>
                                <span class="text-danger fw-bold">
                                    ({{ $each->absents }}/<?php echo isset($currentCourse) ? $currentCourse->{'finished_lesson'} + 1 : ''; ?>)
                                </span>
                                <span class="fw-bold fst-italic"> - P:{{ $each->permission }}</span>
                                <br>
                                <span class="roll fw-lighter fst-italic">
                                    @php
                                        echo '(' . date('d-m-Y', strtotime($each->birthdate)) . ')';
                                    @endphp
                                </span>
                            </td>
                            <td class="text-center border border-0">
                                <input type="radio" class="btn-check" name="students[{{ $loop->index + 1 }}][status]"
                                    value="" id="<?php echo $each->id; ?>_status" checked>
                                <label class="btn btn-outline-success" for="<?php echo $each->id; ?>_status">
                                    Có mặt
                                </label>
                            </td>
                            <td class="text-center border border-0">
                                <input type="radio" class="btn-check" name="students[{{ $loop->index + 1 }}][status]"
                                    value="without reason" id="<?php echo $each->id; ?>no_reason"
                                    {{ $each->currentStatus == 'without reason' ? ' checked' : '' }}>
                                <label class="btn btn-outline-danger" for="<?php echo $each->id; ?>no_reason">
                                    Nghỉ
                                </label>

                            </td>
                            <td class="text-center border border-0">
                                <input type="radio" class="btn-check" name="students[{{ $loop->index + 1 }}][status]"
                                    value="late" id="<?php echo $each->id; ?>late"
                                    {{ $each->currentStatus == 'late' ? ' checked' : '' }}>
                                <label class="btn btn-outline-dark" for="<?php echo $each->id; ?>late">
                                    Muộn
                                </label>
                            </td>
                            <td class="text-center border border-0">
                                <input type="radio" class="btn-check" name="students[{{ $loop->index + 1 }}][status]"
                                    id="<?php echo $each->id; ?>with_reason" autocomplete="off" value="with reason"
                                    {{ $each->currentStatus == 'with reason' ? ' checked' : '' }}>
                                <label class="btn btn-outline-primary" for="<?php echo $each->id; ?>with_reason">
                                    Có phép
                                </label>
                            </td>
                            <td>
                                <input type="text" class="form-control"
                                    name="students[{{ $loop->index + 1 }}][absent_reason]"
                                    id="<?php echo $each->id; ?>_absent_reason" placeholder="Lý do nghỉ (nếu có)"
                                    value = "{{ $each->absentReason }}" ?>
                            </td>
                        </tr>
                    @endforeach
                @endisset
                </tbody>
            </table>
            <br>
            {{-- Lịch sử các buổi học --}}
            <button type="button" class="btn btn-primary" onclick="showPrevLesson()">Lịch sử</button>
            <div class="previous-lesson border border-4 border-primary mt-2 mb-3 pe-4 pt-2 pb-4 ps-3 collapse"
                id="prev-lesson">
                Các buổi học trước: <br>
                @isset($lessons)
                    @foreach ($lessons as $lesson)
                        <a href="{{ route('get_lesson', ['id' => $lesson->id]) }}">
                            <button type="button" class="btn btn-outline-primary ms-1 me-1">
                                @php
                                    echo date('d-m-Y', strtotime($lesson->created_at));
                                @endphp
                            </button>
                        </a>
                    @endforeach
                @endisset
            </div>
            <br>
            {{-- Phần chọn thời gian --}}
            {{-- Nếu đang xem lại chi tiết buổi học trong phần lịch sử --}}
            @isset($curLessonDate)
                Ngày điểm danh: <input type="date" name='lesson-date'
                    class='pt-2 pb-2 ps-2 mb-2 me-4 text-primary fs-5 text-center' value="<?php echo date('Y-d-m', strtotime($curLessonDate)); ?>"
                    placeholder="dd-mm-yyyy" readonly>

                Giờ bắt đầu:
                <span class="time-picker" id="start" name="start">
                    <select class="pt-1 pb-1 ps-2 pe-2 fs-4" name="start[hour]" id="start[hour]" disabled>
                        <option value="00" {{ $lessonStart['hour'] == '00' ? 'selected' : '' }}>00</option>
                        <option value="01" {{ $lessonStart['hour'] == '01' ? 'selected' : '' }}>01</option>
                        <option value="02" {{ $lessonStart['hour'] == '02' ? 'selected' : '' }}>02</option>
                        <option value="03" {{ $lessonStart['hour'] == '03' ? 'selected' : '' }}>03</option>
                        <option value="04" {{ $lessonStart['hour'] == '04' ? 'selected' : '' }}>04</option>
                        <option value="05" {{ $lessonStart['hour'] == '05' ? 'selected' : '' }}>05</option>
                        <option value="06" {{ $lessonStart['hour'] == '06' ? 'selected' : '' }}>06</option>
                        <option value="07" {{ $lessonStart['hour'] == '07' ? 'selected' : '' }}>07</option>
                        <option value="08" {{ $lessonStart['hour'] == '08' ? 'selected' : '' }}>08</option>
                        <option value="09" {{ $lessonStart['hour'] == '09' ? 'selected' : '' }}>09</option>
                        <option value="10" {{ $lessonStart['hour'] == '10' ? 'selected' : '' }}>10</option>
                        <option value="11" {{ $lessonStart['hour'] == '11' ? 'selected' : '' }}>11</option>
                        <option value="12" {{ $lessonStart['hour'] == '12' ? 'selected' : '' }}>12</option>
                        <option value="13" {{ $lessonStart['hour'] == '13' ? 'selected' : '' }}>13</option>
                        <option value="14" {{ $lessonStart['hour'] == '14' ? 'selected' : '' }}>14</option>
                        <option value="15" {{ $lessonStart['hour'] == '15' ? 'selected' : '' }}>15</option>
                        <option value="16" {{ $lessonStart['hour'] == '16' ? 'selected' : '' }}>16</option>
                        <option value="17" {{ $lessonStart['hour'] == '17' ? 'selected' : '' }}>17</option>
                        <option value="18" {{ $lessonStart['hour'] == '18' ? 'selected' : '' }}>18</option>
                        <option value="19" {{ $lessonStart['hour'] == '19' ? 'selected' : '' }}>19</option>
                        <option value="20" {{ $lessonStart['hour'] == '20' ? 'selected' : '' }}>20</option>
                        <option value="21" {{ $lessonStart['hour'] == '21' ? 'selected' : '' }}>21</option>
                        <option value="22" {{ $lessonStart['hour'] == '22' ? 'selected' : '' }}>22</option>
                        <option value="23" {{ $lessonStart['hour'] == '23' ? 'selected' : '' }}>23</option>
                    </select>
                    <span class="fs-4">:</span>
                    <select class="pt-1 pb-1 ps-2 pe-2 fs-4 me-4" name="start[minutes]" id="start[minutes]" disabled>
                        <option value="00" {{ $lessonStart['minutes'] == '00' ? 'selected' : '' }}>00</option>
                        <option value="01" {{ $lessonStart['minutes'] == '01' ? 'selected' : '' }}>01</option>
                        <option value="02" {{ $lessonStart['minutes'] == '02' ? 'selected' : '' }}>02</option>
                        <option value="03" {{ $lessonStart['minutes'] == '03' ? 'selected' : '' }}>03</option>
                        <option value="04" {{ $lessonStart['minutes'] == '04' ? 'selected' : '' }}>04</option>
                        <option value="05" {{ $lessonStart['minutes'] == '05' ? 'selected' : '' }}>05</option>
                        <option value="06" {{ $lessonStart['minutes'] == '06' ? 'selected' : '' }}>06</option>
                        <option value="07" {{ $lessonStart['minutes'] == '07' ? 'selected' : '' }}>07</option>
                        <option value="08" {{ $lessonStart['minutes'] == '08' ? 'selected' : '' }}>08</option>
                        <option value="09" {{ $lessonStart['minutes'] == '09' ? 'selected' : '' }}>09</option>
                        <option value="10" {{ $lessonStart['minutes'] == '10' ? 'selected' : '' }}>10</option>
                        <option value="11" {{ $lessonStart['minutes'] == '11' ? 'selected' : '' }}>11</option>
                        <option value="12" {{ $lessonStart['minutes'] == '12' ? 'selected' : '' }}>12</option>
                        <option value="13" {{ $lessonStart['minutes'] == '13' ? 'selected' : '' }}>13</option>
                        <option value="14" {{ $lessonStart['minutes'] == '14' ? 'selected' : '' }}>14</option>
                        <option value="15" {{ $lessonStart['minutes'] == '15' ? 'selected' : '' }}>15</option>
                        <option value="16" {{ $lessonStart['minutes'] == '16' ? 'selected' : '' }}>16</option>
                        <option value="17" {{ $lessonStart['minutes'] == '17' ? 'selected' : '' }}>17</option>
                        <option value="18" {{ $lessonStart['minutes'] == '18' ? 'selected' : '' }}>18</option>
                        <option value="19" {{ $lessonStart['minutes'] == '19' ? 'selected' : '' }}>19</option>
                        <option value="20" {{ $lessonStart['minutes'] == '20' ? 'selected' : '' }}>20</option>
                        <option value="21" {{ $lessonStart['minutes'] == '21' ? 'selected' : '' }}>21</option>
                        <option value="22" {{ $lessonStart['minutes'] == '22' ? 'selected' : '' }}>22</option>
                        <option value="23" {{ $lessonStart['minutes'] == '23' ? 'selected' : '' }}>23</option>
                        <option value="24" {{ $lessonStart['minutes'] == '24' ? 'selected' : '' }}>24</option>
                        <option value="25" {{ $lessonStart['minutes'] == '25' ? 'selected' : '' }}>25</option>
                        <option value="26" {{ $lessonStart['minutes'] == '26' ? 'selected' : '' }}>26</option>
                        <option value="27" {{ $lessonStart['minutes'] == '27' ? 'selected' : '' }}>27</option>
                        <option value="28" {{ $lessonStart['minutes'] == '28' ? 'selected' : '' }}>28</option>
                        <option value="29" {{ $lessonStart['minutes'] == '29' ? 'selected' : '' }}>29</option>
                        <option value="30" {{ $lessonStart['minutes'] == '30' ? 'selected' : '' }}>30</option>
                        <option value="31" {{ $lessonStart['minutes'] == '31' ? 'selected' : '' }}>31</option>
                        <option value="32" {{ $lessonStart['minutes'] == '32' ? 'selected' : '' }}>32</option>
                        <option value="33" {{ $lessonStart['minutes'] == '33' ? 'selected' : '' }}>33</option>
                        <option value="34" {{ $lessonStart['minutes'] == '34' ? 'selected' : '' }}>34</option>
                        <option value="35" {{ $lessonStart['minutes'] == '35' ? 'selected' : '' }}>35</option>
                        <option value="36" {{ $lessonStart['minutes'] == '36' ? 'selected' : '' }}>36</option>
                        <option value="37" {{ $lessonStart['minutes'] == '37' ? 'selected' : '' }}>37</option>
                        <option value="38" {{ $lessonStart['minutes'] == '38' ? 'selected' : '' }}>38</option>
                        <option value="39" {{ $lessonStart['minutes'] == '39' ? 'selected' : '' }}>39</option>
                        <option value="40" {{ $lessonStart['minutes'] == '40' ? 'selected' : '' }}>40</option>
                        <option value="41" {{ $lessonStart['minutes'] == '41' ? 'selected' : '' }}>41</option>
                        <option value="42" {{ $lessonStart['minutes'] == '42' ? 'selected' : '' }}>42</option>
                        <option value="43" {{ $lessonStart['minutes'] == '43' ? 'selected' : '' }}>43</option>
                        <option value="44" {{ $lessonStart['minutes'] == '44' ? 'selected' : '' }}>44</option>
                        <option value="45" {{ $lessonStart['minutes'] == '45' ? 'selected' : '' }}>45</option>
                        <option value="46" {{ $lessonStart['minutes'] == '46' ? 'selected' : '' }}>46</option>
                        <option value="47" {{ $lessonStart['minutes'] == '47' ? 'selected' : '' }}>47</option>
                        <option value="48" {{ $lessonStart['minutes'] == '48' ? 'selected' : '' }}>48</option>
                        <option value="49" {{ $lessonStart['minutes'] == '49' ? 'selected' : '' }}>49</option>
                        <option value="50" {{ $lessonStart['minutes'] == '50' ? 'selected' : '' }}>50</option>
                        <option value="51" {{ $lessonStart['minutes'] == '51' ? 'selected' : '' }}>51</option>
                        <option value="52" {{ $lessonStart['minutes'] == '52' ? 'selected' : '' }}>52</option>
                        <option value="53" {{ $lessonStart['minutes'] == '53' ? 'selected' : '' }}>53</option>
                        <option value="54" {{ $lessonStart['minutes'] == '54' ? 'selected' : '' }}>54</option>
                        <option value="55" {{ $lessonStart['minutes'] == '55' ? 'selected' : '' }}>55</option>
                        <option value="56" {{ $lessonStart['minutes'] == '56' ? 'selected' : '' }}>56</option>
                        <option value="57" {{ $lessonStart['minutes'] == '57' ? 'selected' : '' }}>57</option>
                        <option value="58" {{ $lessonStart['minutes'] == '58' ? 'selected' : '' }}>58</option>
                        <option value="59" {{ $lessonStart['minutes'] == '59' ? 'selected' : '' }}>59</option>
                    </select>
                </span>

                Giờ kết thúc:
                <span class="time-picker" id="end" name="end">
                    <select class="pt-1 pb-1 ps-2 pe-2 fs-4" name="end[hour]" id="end[hour]" disabled>
                        <option value="00" {{ $lessonEnd['hour'] == '00' ? 'selected' : '' }}>00</option>
                        <option value="01" {{ $lessonEnd['hour'] == '01' ? 'selected' : '' }}>01</option>
                        <option value="02" {{ $lessonEnd['hour'] == '02' ? 'selected' : '' }}>02</option>
                        <option value="03" {{ $lessonEnd['hour'] == '03' ? 'selected' : '' }}>03</option>
                        <option value="04" {{ $lessonEnd['hour'] == '04' ? 'selected' : '' }}>04</option>
                        <option value="05" {{ $lessonEnd['hour'] == '05' ? 'selected' : '' }}>05</option>
                        <option value="06" {{ $lessonEnd['hour'] == '06' ? 'selected' : '' }}>06</option>
                        <option value="07" {{ $lessonEnd['hour'] == '07' ? 'selected' : '' }}>07</option>
                        <option value="08" {{ $lessonEnd['hour'] == '08' ? 'selected' : '' }}>08</option>
                        <option value="09" {{ $lessonEnd['hour'] == '09' ? 'selected' : '' }}>09</option>
                        <option value="10" {{ $lessonEnd['hour'] == '10' ? 'selected' : '' }}>10</option>
                        <option value="11" {{ $lessonEnd['hour'] == '11' ? 'selected' : '' }}>11</option>
                        <option value="12" {{ $lessonEnd['hour'] == '12' ? 'selected' : '' }}>12</option>
                        <option value="13" {{ $lessonEnd['hour'] == '13' ? 'selected' : '' }}>13</option>
                        <option value="14" {{ $lessonEnd['hour'] == '14' ? 'selected' : '' }}>14</option>
                        <option value="15" {{ $lessonEnd['hour'] == '15' ? 'selected' : '' }}>15</option>
                        <option value="16" {{ $lessonEnd['hour'] == '16' ? 'selected' : '' }}>16</option>
                        <option value="17" {{ $lessonEnd['hour'] == '17' ? 'selected' : '' }}>17</option>
                        <option value="18" {{ $lessonEnd['hour'] == '18' ? 'selected' : '' }}>18</option>
                        <option value="19" {{ $lessonEnd['hour'] == '19' ? 'selected' : '' }}>19</option>
                        <option value="20" {{ $lessonEnd['hour'] == '20' ? 'selected' : '' }}>20</option>
                        <option value="21" {{ $lessonEnd['hour'] == '21' ? 'selected' : '' }}>21</option>
                        <option value="22" {{ $lessonEnd['hour'] == '22' ? 'selected' : '' }}>22</option>
                        <option value="23" {{ $lessonEnd['hour'] == '23' ? 'selected' : '' }}>23</option>
                    </select>
                    <span class="fs-4">:</span>
                    <select class="pt-1 pb-1 ps-2 pe-2 fs-4" name="end[minutes]" id="end[minutes]" disabled>
                        <option value="00" {{ $lessonEnd['minutes'] == '00' ? 'selected' : '' }}>00</option>
                        <option value="01" {{ $lessonEnd['minutes'] == '01' ? 'selected' : '' }}>01</option>
                        <option value="02" {{ $lessonEnd['minutes'] == '02' ? 'selected' : '' }}>02</option>
                        <option value="03" {{ $lessonEnd['minutes'] == '03' ? 'selected' : '' }}>03</option>
                        <option value="04" {{ $lessonEnd['minutes'] == '04' ? 'selected' : '' }}>04</option>
                        <option value="05" {{ $lessonEnd['minutes'] == '05' ? 'selected' : '' }}>05</option>
                        <option value="06" {{ $lessonEnd['minutes'] == '06' ? 'selected' : '' }}>06</option>
                        <option value="07" {{ $lessonEnd['minutes'] == '07' ? 'selected' : '' }}>07</option>
                        <option value="08" {{ $lessonEnd['minutes'] == '08' ? 'selected' : '' }}>08</option>
                        <option value="09" {{ $lessonEnd['minutes'] == '09' ? 'selected' : '' }}>09</option>
                        <option value="10" {{ $lessonEnd['minutes'] == '10' ? 'selected' : '' }}>10</option>
                        <option value="11" {{ $lessonEnd['minutes'] == '11' ? 'selected' : '' }}>11</option>
                        <option value="12" {{ $lessonEnd['minutes'] == '12' ? 'selected' : '' }}>12</option>
                        <option value="13" {{ $lessonEnd['minutes'] == '13' ? 'selected' : '' }}>13</option>
                        <option value="14" {{ $lessonEnd['minutes'] == '14' ? 'selected' : '' }}>14</option>
                        <option value="15" {{ $lessonEnd['minutes'] == '15' ? 'selected' : '' }}>15</option>
                        <option value="16" {{ $lessonEnd['minutes'] == '16' ? 'selected' : '' }}>16</option>
                        <option value="17" {{ $lessonEnd['minutes'] == '17' ? 'selected' : '' }}>17</option>
                        <option value="18" {{ $lessonEnd['minutes'] == '18' ? 'selected' : '' }}>18</option>
                        <option value="19" {{ $lessonEnd['minutes'] == '19' ? 'selected' : '' }}>19</option>
                        <option value="20" {{ $lessonEnd['minutes'] == '20' ? 'selected' : '' }}>20</option>
                        <option value="21" {{ $lessonEnd['minutes'] == '21' ? 'selected' : '' }}>21</option>
                        <option value="22" {{ $lessonEnd['minutes'] == '22' ? 'selected' : '' }}>22</option>
                        <option value="23" {{ $lessonEnd['minutes'] == '23' ? 'selected' : '' }}>23</option>
                        <option value="24" {{ $lessonEnd['minutes'] == '24' ? 'selected' : '' }}>24</option>
                        <option value="25" {{ $lessonEnd['minutes'] == '25' ? 'selected' : '' }}>25</option>
                        <option value="26" {{ $lessonEnd['minutes'] == '26' ? 'selected' : '' }}>26</option>
                        <option value="27" {{ $lessonEnd['minutes'] == '27' ? 'selected' : '' }}>27</option>
                        <option value="28" {{ $lessonEnd['minutes'] == '28' ? 'selected' : '' }}>28</option>
                        <option value="29" {{ $lessonEnd['minutes'] == '29' ? 'selected' : '' }}>29</option>
                        <option value="30" {{ $lessonEnd['minutes'] == '30' ? 'selected' : '' }}>30</option>
                        <option value="31" {{ $lessonEnd['minutes'] == '31' ? 'selected' : '' }}>31</option>
                        <option value="32" {{ $lessonEnd['minutes'] == '32' ? 'selected' : '' }}>32</option>
                        <option value="33" {{ $lessonEnd['minutes'] == '33' ? 'selected' : '' }}>33</option>
                        <option value="34" {{ $lessonEnd['minutes'] == '34' ? 'selected' : '' }}>34</option>
                        <option value="35" {{ $lessonEnd['minutes'] == '35' ? 'selected' : '' }}>35</option>
                        <option value="36" {{ $lessonEnd['minutes'] == '36' ? 'selected' : '' }}>36</option>
                        <option value="37" {{ $lessonEnd['minutes'] == '37' ? 'selected' : '' }}>37</option>
                        <option value="38" {{ $lessonEnd['minutes'] == '38' ? 'selected' : '' }}>38</option>
                        <option value="39" {{ $lessonEnd['minutes'] == '39' ? 'selected' : '' }}>39</option>
                        <option value="40" {{ $lessonEnd['minutes'] == '40' ? 'selected' : '' }}>40</option>
                        <option value="41" {{ $lessonEnd['minutes'] == '41' ? 'selected' : '' }}>41</option>
                        <option value="42" {{ $lessonEnd['minutes'] == '42' ? 'selected' : '' }}>42</option>
                        <option value="43" {{ $lessonEnd['minutes'] == '43' ? 'selected' : '' }}>43</option>
                        <option value="44" {{ $lessonEnd['minutes'] == '44' ? 'selected' : '' }}>44</option>
                        <option value="45" {{ $lessonEnd['minutes'] == '45' ? 'selected' : '' }}>45</option>
                        <option value="46" {{ $lessonEnd['minutes'] == '46' ? 'selected' : '' }}>46</option>
                        <option value="47" {{ $lessonEnd['minutes'] == '47' ? 'selected' : '' }}>47</option>
                        <option value="48" {{ $lessonEnd['minutes'] == '48' ? 'selected' : '' }}>48</option>
                        <option value="49" {{ $lessonEnd['minutes'] == '49' ? 'selected' : '' }}>49</option>
                        <option value="50" {{ $lessonEnd['minutes'] == '50' ? 'selected' : '' }}>50</option>
                        <option value="51" {{ $lessonEnd['minutes'] == '51' ? 'selected' : '' }}>51</option>
                        <option value="52" {{ $lessonEnd['minutes'] == '52' ? 'selected' : '' }}>52</option>
                        <option value="53" {{ $lessonEnd['minutes'] == '53' ? 'selected' : '' }}>53</option>
                        <option value="54" {{ $lessonEnd['minutes'] == '54' ? 'selected' : '' }}>54</option>
                        <option value="55" {{ $lessonEnd['minutes'] == '55' ? 'selected' : '' }}>55</option>
                        <option value="56" {{ $lessonEnd['minutes'] == '56' ? 'selected' : '' }}>56</option>
                        <option value="57" {{ $lessonEnd['minutes'] == '57' ? 'selected' : '' }}>57</option>
                        <option value="58" {{ $lessonEnd['minutes'] == '58' ? 'selected' : '' }}>58</option>
                        <option value="59" {{ $lessonEnd['minutes'] == '59' ? 'selected' : '' }}>59</option>
                    </select>
                </span>
            @else
                Ngày điểm danh: <input type="date" name='lesson-date'
                    class='pt-2 pb-2 ps-2 mb-2 me-4 text-primary fs-5 text-center' value="<?php echo date('Y-m-d'); ?>"
                    placeholder="dd-mm-yyyy" readonly>

                Giờ bắt đầu:
                <span class="time-picker" id="start" name="start">
                    <select class="pt-1 pb-1 ps-2 pe-2 fs-4" name="start[hour]" id="start[hour]">
                        <option value="00">00</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08" selected>08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21" disabled>21</option>
                        <option value="22" disabled>22</option>
                        <option value="23" disabled>23</option>
                    </select>
                    <span class="fs-4">:</span>
                    <select class="pt-1 pb-1 ps-2 pe-2 fs-4 me-4" name="start[minutes]" id="start[minutes]">
                        <option value="00" selected>00</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                        <option value="32">32</option>
                        <option value="33">33</option>
                        <option value="34">34</option>
                        <option value="35">35</option>
                        <option value="36">36</option>
                        <option value="37">37</option>
                        <option value="38">38</option>
                        <option value="39">39</option>
                        <option value="40">40</option>
                        <option value="41">41</option>
                        <option value="42">42</option>
                        <option value="43">43</option>
                        <option value="44">44</option>
                        <option value="45">45</option>
                        <option value="46">46</option>
                        <option value="47">47</option>
                        <option value="48">48</option>
                        <option value="49">49</option>
                        <option value="50">50</option>
                        <option value="51">51</option>
                        <option value="52">52</option>
                        <option value="53">53</option>
                        <option value="54">54</option>
                        <option value="55">55</option>
                        <option value="56">56</option>
                        <option value="57">57</option>
                        <option value="58">58</option>
                        <option value="59">59</option>
                    </select>
                </span>

                Giờ kết thúc:
                <span class="time-picker" id="end" name="end">
                    <select class="pt-1 pb-1 ps-2 pe-2 fs-4" name="end[hour]" id="end[hour]">
                        <option value="00">00</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12" selected>12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23" disabled>23</option>
                    </select>
                    <span class="fs-4">:</span>
                    <select class="pt-1 pb-1 ps-2 pe-2 fs-4" name="end[minutes]" id="end[minutes]">
                        <option value="00" selected>00</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                        <option value="32">32</option>
                        <option value="33">33</option>
                        <option value="34">34</option>
                        <option value="35">35</option>
                        <option value="36">36</option>
                        <option value="37">37</option>
                        <option value="38">38</option>
                        <option value="39">39</option>
                        <option value="40">40</option>
                        <option value="41">41</option>
                        <option value="42">42</option>
                        <option value="43">43</option>
                        <option value="44">44</option>
                        <option value="45">45</option>
                        <option value="46">46</option>
                        <option value="47">47</option>
                        <option value="48">48</option>
                        <option value="49">49</option>
                        <option value="50">50</option>
                        <option value="51">51</option>
                        <option value="52">52</option>
                        <option value="53">53</option>
                        <option value="54">54</option>
                        <option value="55">55</option>
                        <option value="56">56</option>
                        <option value="57">57</option>
                        <option value="58">58</option>
                        <option value="59">59</option>
                    </select>
                </span>
            @endisset

            <textarea class="form-control mb-4 mt-4" placeholder="Ghi chú:" rows="4"></textarea>
            {{-- <button class="btn btn-primary" data-toggle="modal" data-target="bs-example-modal-sm">Hỗ trợ</button> --}}
            @isset($list)
                @isset($curLessonDate)
                </form>
                <form action="{{ route('course_attendance') }}" method="POST">
                    @csrf
                    <input type="hidden" name='course-id' value='<?php echo $currentCourse->id; ?>'>
                    <button id="" class="btn btn-success" type="">
                        Trở về buổi học hiện tại
                    </button>
                </form>
            @else
                <button id="submit" class="btn btn-success" type="submit">Lưu điểm danh</button>
                </form>
            @endisset
        @endisset
    </div>

    <script>
        function validateForm() {
            // Get current time
            let now = new Date();
            let curHour = (now.getHours() < 10) ? ("0" + now.getHours()) : now.getHours();
            let curMinutes = (now.getMinutes() < 10) ? ("0" + now.getMinutes()) : now.getMinutes();
            let current = curHour + ":" + curMinutes;
            // Get data
            let start = document.forms["attendanceForm"]["start[hour]"].value + ":" + document.forms["attendanceForm"][
                "start[minutes]"
            ].value;
            let end = document.forms["attendanceForm"]["end[hour]"].value + ":" + document.forms["attendanceForm"][
                "end[minutes]"
            ].value;
            // VALIDATE
            try {
                // Seperate data to calculate
                let curArr = current.split(":");
                let endArr = end.split(":");
                let startArr = start.split(":");

                // - Giờ bắt đầu không sớm hơn giờ kết thúc
                // - Giờ bắt đầu không sớm hơn hiện tại
                // - Giờ kết thúc không muộn hơn hiện tại quá 30p
                if (start > end || start > current) {
                    alert("Thời gian buổi học không hợp lệ");
                    return false;
                }
                if ((curArr[0] - endArr[0] == 0 && curArr[1] - endArr[1] > 30) || (curArr[0] - endArr[0] > 0)) {
                    alert("Buổi học đã kết thúc quá 30 phút");
                    return false;
                }
            } catch (err) {
                console.log(err.message);
            }
        }

        function currentDate() {
            let currentDate = new Date()
            let maxDate = currentDate.toISOString().split('T')[0];
            document.getElementsByName("lesson-date")[0].setAttribute('max', maxDate);
        }

        function showPrevLesson() {
            let x = document.getElementById("prev-lesson");
            if (x.style.display == "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }

        function courseSearch() {
            // Declare variables
            var input, filter, ul, courses, a, i, txtValue;
            input = document.getElementById('courseSearchInput');
            filter = input.value.toUpperCase();
            ul = document.getElementById("class_selector");
            courses = ul.getElementsByName('select_course');

            // Loop through all list items, and hide those who don't match the search query
            for (i = 0; i < courses.length; i++) {
                txtValue = courses.textContent || courses.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    courses[i].style.display = "";
                } else {
                    courses[i].style.display = "none";
                }
            }
        }
    </script>
    <script type="text/javascript">
        const selected = document.querySelector(".selected");
        const courseContainer = document.querySelector(".course-container");
        const searchBox = document.querySelector(".search-box input");

        const courses = document.querySelectorAll(".course");

        selected.addEventListener("click", () => {
            courseContainer.classList.toggle("active");

            searchBox.value = "";
            filterList("");

            if (courseContainer.classList.contains("active")) {
                searchBox.focus();
            }
        });

        courses.forEach(o => {
            o.addEventListener("click", () => {
                selected.innerHTML = o.querySelector("label").innerHTML;
                courseContainer.classList.remove("active");
            });
        });

        searchBox.addEventListener("keyup", function(e) {
            filterList(e.target.value);
        });

        const filterList = searchTerm => {
            // Viết thường, xóa khoảng trắng hoặc kí tự đặc biệt
            searchTerm = searchTerm.toLowerCase();
            searchTerm = searchTerm.replace('-', '');
            searchTerm = searchTerm.replace('_', '');
            searchTerm = searchTerm.replace('/', '');
            searchTerm = searchTerm.replace('  ', '');
            searchTerm = searchTerm.replace(' ', '');

            courses.forEach(option => {
                // Xóa khoảng trắng hoặc kí tự đặc biệt
                let label = option.firstElementChild.nextElementSibling.innerText.toLowerCase();
                label = label.replace('-', '');
                label = label.replace('_', '');
                label = label.replace('/', '');
                label = label.replace('  ', '');
                label = label.replace(' ', '');
                if (label.indexOf(searchTerm) != -1) {
                    option.style.display = "block";
                } else {
                    option.style.display = "none";
                }
            });
        };
    </script>
@endsection
{{-- @section('course-dropdown-js') --}}
{{-- @endsection --}}
