@extends('layouts.app')

@section('title', 'إضافة مرشح جديد')
@section('page-title', 'إضافة مرشح لانتخابات: ' . $election->name)

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('assistant.elections.candidates.store', $election) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">اسم المرشح</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">الوصف</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">حفظ</button>
        </form>
    </div>
</div>
@endsection
