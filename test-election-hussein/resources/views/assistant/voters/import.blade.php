@extends('layouts.app')

@section('title', 'رفع بيانات الناخبين')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">رفع بيانات الناخبين من ملف Excel</h4>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <p>
                        قم بتحميل ملف Excel أو CSV يحتوي على بيانات الناخبين. تأكد من أن الملف يحتوي على الأعمدة المطلوبة بالترتيب الصحيح.
                    </p>
                    <p>
                        <a href="#" class="btn btn-sm btn-secondary">تحميل نموذج الملف</a>
                    </p>
                    <hr>

                    <form method="POST" action="{{ route('assistant.voters.import.submit') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="file" class="form-label">اختر الملف</label>
                            <input class="form-control" type="file" id="file" name="file" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>
                                بدء عملية الرفع
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection
