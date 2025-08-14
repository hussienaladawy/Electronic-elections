@extends('layouts.app')

@section('title', 'مرشحين الانتخابات')
@section('page-title', 'مرشحين انتخابات: ' . $election->name)

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('assistant.elections.candidates.create', $election) }}" class="btn btn-primary">إضافة مرشح</a>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم المرشح</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($candidates as $candidate)
                    <tr>
                        <td>{{ $candidate->id }}</td>
                        <td>{{ $candidate->name }}</td>
                        <td>
                            <a href="{{ route('assistant.elections.candidates.edit', [$election, $candidate]) }}" class="btn btn-secondary btn-sm">تعديل</a>
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
