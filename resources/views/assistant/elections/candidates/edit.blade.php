@extends('layouts.app')

@section('title', 'تعديل مرشح')
@section('page-title', 'تعديل مرشح في انتخابات: ' . $election->name)

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('assistant.elections.candidates.update', [$election, $candidate]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">اسم المرشح</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $candidate->name }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">الوصف</label>
                <textarea name="description" id="description" class="form-control">{{ $candidate->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">تحديث</button>
        </form>
    </div>
</div>
@endsection
