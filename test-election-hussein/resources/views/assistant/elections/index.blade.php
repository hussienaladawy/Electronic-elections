@extends('layouts.app')

@section('title', 'الانتخابات')
@section('page-title', 'قائمة الانتخابات')

@section('content')
<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم الانتخابات</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($elections as $election)
                    <tr>
                        <td>{{ $election->id }}</td>
                        <td>{{ $election->name }}</td>
                        <td>{{ $election->status }}</td>
                        <td>
                            <a href="{{ route('assistant.elections.candidates.index', $election) }}" class="btn btn-primary btn-sm">المرشحين</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection
