@extends('layout')

@section('title', 'Admin Dashboard')

@section('content')
    <h2>Chào mừng, {{ session('name') }}</h2><br>
    <h3>Vui lòng nhấp vào sidebar để quản lí thông tin điểm danh</h3>
@endsection
