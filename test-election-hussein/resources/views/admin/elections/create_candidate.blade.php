@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">اضافة مرشح جديد</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.elections.candidates.store', $election->id) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">اسم المرشح</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                          <div class="mb-3">
                            <label for="order_number" class="form-label">رقم المرشح</label>
                            <input type="order_number" class="form-control @error('order_number') is-invalid @enderror" id="order_number" name="order_number" value="{{ old('order_number') }}">
                            @error('number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <!-- الصورة -->
                             <div class="mb-3">
                            <label for="image" class="form-label">صورة  (المرشح)</label>
                            <input type="file" name="image" id="image" class="form-control @error("image") is-invalid @enderror" accept="image/*">
                            @error("image")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">الحد الأقصى: 5MB، الأنواع المدعومة: JPG, PNG, GIF</small>
                        </div>

                        <div class="mb-3">
                            <label for="party" class="form-label">حزب (اختياري)</label>
                            <input type="text" class="form-control @error('party') is-invalid @enderror" id="party" name="party" value="{{ old('party') }}">
                            @error('party')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">سيرة ذاتية (اختياري)</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="3">{{ old('bio') }}</textarea>
                            @error('bio')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">اضافة المرشح جديد</button>
                        <a href="{{ route('admin.elections.candidates', $election->id) }}" class="btn btn-secondary">الغاء</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
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
</script>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>



