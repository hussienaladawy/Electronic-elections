@extends("layouts.app")

@section("title", "إضافة انتخابات جديدة")

@section("content")
<div class="container-fluid">
    <!-- Page Heading -->
     @can('manage elections')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">إضافة انتخابات جديدة</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">لوحة التحكم</a></li>
                <li class="breadcrumb-item"><a href="{{ route("admin.elections.index") }}">الانتخابات</a></li>
                <li class="breadcrumb-item active" aria-current="page">إضافة جديد</li>
            </ol>
        </nav>
    </div>
    @endcan
    

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">معلومات الانتخابات</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route("admin.elections.store") }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">عنوان الانتخابات <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error("title") is-invalid @enderror" 
                                   value="{{ old("title") }}" required>
                            @error("title")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">الوصف</label>
                            <textarea name="description" id="description" class="form-control @error("description") is-invalid @enderror" rows="4">{{ old("description") }}</textarea>
                            @error("description")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
<div class="row">
   <div class="col-md-6 mb-3">
    <label for="registration_start" class="form-label">بدء التسجيل <span class="text-danger">*</span></label>
    <input type="datetime-local" name="registration_start" id="registration_start"
           class="form-control @error('registration_start') is-invalid @enderror"
           value="{{ old('registration_start') }}"
           min="{{ now()->format('Y-m-d\TH:i') }}"
           placeholder="مثال: 2025-08-12T01:00"
           required>
    <small class="form-text text-muted">التنسيق: يوم/شهر/سنة ساعة:دقيقة (مثال: 12/08/2025 01:00)</small>
    @error('registration_start')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-6 mb-3">
    <label for="registration_end" class="form-label">انتهاء التسجيل <span class="text-danger">*</span></label>
    <input type="datetime-local" name="registration_end" id="registration_end"
           class="form-control @error('registration_end') is-invalid @enderror"
           value="{{ old('registration_end') }}"
           placeholder="مثال: 2025-12-25T12:09"
           required>
    <small class="form-text text-muted">التنسيق: يوم/شهر/سنة ساعة:دقيقة (مثال: 25/12/2025 12:09)</small>
    @error('registration_end')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

</div>

<div class="row">
   <div class="col-md-6 mb-3">
    <label for="start_date" class="form-label">تاريخ بدء الانتخاب <span class="text-danger">*</span></label>
    <input type="datetime-local" name="start_date" id="start_date"
           class="form-control @error('start_date') is-invalid @enderror"
           value="{{ old('start_date') }}"
           placeholder="مثال: 2025-08-12T01:00"
           required>
    <small class="form-text text-muted">التنسيق: يوم/شهر/سنة ساعة:دقيقة (مثال: 12/08/2025 01:00)</small>
    @error('start_date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-6 mb-3">
    <label for="end_date" class="form-label">تاريخ انتهاء الانتخاب <span class="text-danger">*</span></label>
    <input type="datetime-local" name="end_date" id="end_date"
           class="form-control @error('end_date') is-invalid @enderror"
           value="{{ old('end_date') }}"
           placeholder="مثال: 2025-09-20T14:00"
           required>
    <small class="form-text text-muted">التنسيق: يوم/شهر/سنة ساعة:دقيقة (مثال: 20/09/2025 14:00)</small>
    @error('end_date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

</div>

                        <div class="mb-3">
                            <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control @error("status") is-invalid @enderror" required>
                                <option value="pending" {{ old("status") == "pending" ? "selected" : "" }}>معلقة</option>
                                <option value="active" {{ old("status") == "active" ? "selected" : "" }}>نشطة</option>
                                <option value="completed" {{ old("status") == "completed" ? "selected" : "" }}>مكتملة</option>
                                <option value="cancelled" {{ old("status") == "cancelled" ? "selected" : "" }}>ملغاة</option>
                            </select>
                            @error("status")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

<div class="mb-3">
    <label for="type" class="form-label">النوع <span class="text-danger">*</span></label>
    <select name="type" id="type" class="form-control @error("type") is-invalid @enderror" required>
       
        <option value="presidential" {{ old("type") == "presidential" ? "selected" : "" }}>رئاسي</option>
        <option value="parliamentary" {{ old("type") == "parliamentary" ? "selected" : "" }}>برلماني</option>
        <option value="local" {{ old("type") == "local" ? "selected" : "" }}>محلي</option>
        <option value="referendum" {{ old("type") == "referendum" ? "selected" : "" }}>استفتاء</option>

    </select>
    @error("type")
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="election_type" class="form-label">نوع الانتخابات <span class="text-danger">*</span></label>
                            <select name="election_type" id="election_type" class="form-control @error("election_type") is-invalid @enderror" required>
                                <option value="single_choice" {{ old("election_type") == "single_choice" ? "selected" : "" }}>اختيار واحد</option>
                                <option value="multiple_choice" {{ old("election_type") == "multiple_choice" ? "selected" : "" }}>اختيار متعدد</option>
                            </select>
                            @error("election_type")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="voting_method" class="form-label">طريقة التصويت <span class="text-danger">*</span></label>
                            <select name="voting_method" id="voting_method" class="form-control @error("voting_method") is-invalid @enderror" required>
                                <option value="online" {{ old("voting_method") == "online" ? "selected" : "" }}>إلكتروني (عبر الإنترنت)</option>
                                <option value="physical" {{ old("voting_method") == "physical" ? "selected" : "" }}>حضوري (فيزيائي)</option>
                            </select>
                            @error("voting_method")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">صورة الانتخابات (اختياري)</label>
                            <input type="file" name="image" id="image" class="form-control @error("image") is-invalid @enderror" accept="image/*">
                            @error("image")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">الحد الأقصى: 5MB، الأنواع المدعومة: JPG, PNG, GIF</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route("admin.elections.index") }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right mr-1"></i>
                                العودة
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                حفظ الانتخابات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">إرشادات</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <h6 class="alert-heading">معلومات مهمة:</h6>
                        <ul class="mb-0 small">
                            <li>الحقول المميزة بـ <span class="text-danger">*</span> مطلوبة.</li>
                            <li>تاريخ البدء يجب أن يكون قبل تاريخ الانتهاء.</li>
                            <li>يمكنك إضافة وصف تفصيلي للانتخابات.</li>
                            <li>اختر نوع الانتخابات (اختيار واحد أو متعدد).</li>
                            <li>حدد طريقة التصويت (إلكتروني أو حضوري).</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning" role="alert">
                        <h6 class="alert-heading">ملاحظات على حالة الانتخابات:</h6>
                        <ul class="mb-0 small">
                            <li>**معلقة:** الانتخابات لم تبدأ بعد.</li>
                            <li>**نشطة:** الانتخابات جارية ويمكن للناخبين التصويت.</li>
                            <li>**مكتملة:** الانتخابات انتهت وتم فرز الأصوات.</li>
                            <li>**ملغاة:** تم إلغاء الانتخابات لأي سبب.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.getElementById('registration_start').addEventListener('change', function () {
        document.getElementById('registration_end').min = this.value;
    });

    document.getElementById('registration_end').addEventListener('change', function () {
        document.getElementById('start_date').min = this.value;
    });

    document.getElementById('start_date').addEventListener('change', function () {
        document.getElementById('end_date').min = this.value;
    });
</script>
@endpush
<script>
document.addEventListener("DOMContentLoaded", function() {
    const startDateInput = document.getElementById("start_date");
    const endDateInput = document.getElementById("end_date");

    startDateInput.addEventListener("change", function() {
        if (endDateInput.value && this.value > endDateInput.value) {
            endDateInput.value = this.value;
        }
    });

    endDateInput.addEventListener("change", function() {
        if (startDateInput.value && this.value < startDateInput.value) {
            startDateInput.value = this.value;
        }
    });

    const imageInput = document.getElementById("image");
    imageInput.addEventListener("change", function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.getElementById("image-preview");
                if (!preview) {
                    preview = document.createElement("img");
                    preview.id = "image-preview";
                    preview.className = "img-thumbnail mt-2";
                    preview.style.maxWidth = "150px";
                    imageInput.parentNode.appendChild(preview);
                }
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection

