@extends("layouts.app")

@section("title", "التحقق من التصويت")

@section("content")
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">التحقق من التصويت</div>

                <div class="card-body">
                    @if(session("success"))
                        <div class="alert alert-success" role="alert">
                            {{ session("success") }}
                        </div>
                    @endif

                    @if(session("error"))
                        <div class="alert alert-danger" role="alert">
                            {{ session("error") }}
                        </div>
                    @endif

                    @if(isset($vote) && $vote)
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">تم التحقق من التصويت بنجاح!</h4>
                            <p>تفاصيل التصويت:</p>
                            <ul>
                                <li><strong>رمز التصويت:</strong> {{ $vote->vote_code }}</li>
                                <li><strong>الناخب:</strong> {{ $vote->voter->name ?? 'غير معروف' }}</li>
                                <li><strong>الانتخابات:</strong> {{ $vote->election->title ?? 'غير معروف' }}</li>
                                <li><strong>المرشح:</strong> {{ $vote->candidate->name ?? 'غير معروف' }}</li>
                                <li><strong>تاريخ التصويت:</strong> {{ $vote->created_at->format('Y-m-d H:i:s') }}</li>
                            </ul>
                            <hr>
                            <p class="mb-0">هذا التصويت مسجل في نظامنا وهو صالح.</p>
                        </div>
                    @elseif(request()->has("vote_code"))
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">فشل التحقق من التصويت!</h4>
                            <p>رمز التصويت الذي أدخلته غير صالح أو غير موجود.</p>
                            <hr>
                            <p class="mb-0">يرجى التأكد من إدخال الرمز الصحيح والمحاولة مرة أخرى.</p>
                        </div>
                    @endif

                    <form action="{{ route('public.verify') }}" method="GET">
                        <div class="mb-3">
                            <label for="vote_code" class="form-label">أدخل رمز التصويت:</label>
                            <input type="text" class="form-control" id="vote_code" name="vote_code" value="{{ request('vote_code') }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">تحقق</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

