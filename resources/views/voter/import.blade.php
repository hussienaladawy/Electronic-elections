@extends('layouts.app')

@section('title', 'استيراد الناخبين')

@section('content')
<div class="container">
    <h1 class="mb-4">استيراد الناخبين</h1>

    <form action="{{ route('voters.import.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">ملف الناخبين (Excel أو CSV)</label>
            <input type="file" class="form-control" name="file" id="file" required>
        </div>
        <button type="submit" class="btn btn-success">استيراد</button>
        <a href="{{ route('super_admin.voters.import.template') }}" class="btn btn-info">تحميل النموذج</a>
    </form>
</div>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection