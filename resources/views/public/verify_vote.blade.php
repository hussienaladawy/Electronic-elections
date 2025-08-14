@extends("layouts.app")

@section("title", "التحقق من التصويت")

@section("content")
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">التحقق من التصويت</div>

                <div class="card-body">
             @if(request()->has('vote_code') && $vote)
    <div class="alert alert-success" role="alert">
        <!-- تفاصيل التصويت -->
    </div>
@elseif(request()->has('vote_code'))
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">فشل التحقق من التصويت!</h4>
        <p>رمز التصويت الذي أدخلته غير صالح أو غير موجود.</p>
        <hr>
        <p class="mb-0">يرجى التأكد من إدخال الرمز الصحيح والمحاولة مرة أخرى.</p>
    </div>
@endif


                    <form action="{{ route('public.verify_vote') }}" method="GET">
                        <div class="mb-3">
                            <label for="vote_code" class="form-label">أدخل رمز التصويت:</label>
                            <input type="text" class="form-control" id="vote_code" name="vote_code" required>
                        </div>
                        <button type="submit" class="btn btn-primary">تحقق</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


