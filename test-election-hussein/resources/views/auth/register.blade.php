<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل ناخب جديد - نظام الانتخابات الإلكترونية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        
        .register-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 2rem 0;
        }
        
        .register-header {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .register-form {
            padding: 2rem;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-register {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            padding: 12px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: white;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .back-link {
            color: #667eea;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            position: relative;
        }
        
        .step.active {
            background: #667eea;
            color: white;
        }
        
        .step.completed {
            background: #28a745;
            color: white;
        }
        
        .step::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 100%;
            width: 20px;
            height: 2px;
            background: #e9ecef;
            transform: translateY(-50%);
        }
        
        .step:last-child::after {
            display: none;
        }
        
        .step.completed::after {
            background: #28a745;
        }
        
        .password-strength {
            margin-top: 5px;
        }
        
        .strength-bar {
            height: 4px;
            border-radius: 2px;
            background: #e9ecef;
            overflow: hidden;
        }
        
        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            width: 0%;
        }
        
        .strength-weak { background: #dc3545; }
        .strength-medium { background: #ffc107; }
        .strength-strong { background: #28a745; }
        
        .form-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e9ecef;
        }
        
        .section-title {
            color: #667eea;
            font-weight: bold;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="register-container">
                    <!-- رأس النموذج -->
                    <div class="register-header">
                        <h2 class="mb-3">
                            <i class="fas fa-user-plus me-2"></i>
                            تسجيل ناخب جديد
                        </h2>
                        <p class="mb-0">انضم إلى نظام الانتخابات الإلكترونية الآمن</p>
                    </div>
                    
                    <!-- نموذج التسجيل -->
                    <div class="register-form">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>يرجى تصحيح الأخطاء التالية:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('voter.register.submit') }}" id="registerForm">
                            @csrf
                            
                            <!-- البيانات الشخصية -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-user"></i>
                                    البيانات الشخصية
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label fw-bold">
                                            <i class="fas fa-signature me-2"></i>
                                            الاسم الكامل *
                                        </label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="{{ old('name') }}" required>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="national_id" class="form-label fw-bold">
                                            <i class="fas fa-id-card me-2"></i>
                                            رقم الهوية الوطنية *
                                        </label>
                                        <input type="text" class="form-control" id="national_id" name="national_id" 
                                               value="{{ old('national_id') }}" required>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="date_of_birth" class="form-label fw-bold">
                                            <i class="fas fa-calendar me-2"></i>
                                            تاريخ الميلاد *
                                        </label>
                                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                               value="{{ old('date_of_birth') }}" required>
                                        <small class="text-muted">يجب أن تكون 18 سنة أو أكثر</small>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="gender" class="form-label fw-bold">
                                            <i class="fas fa-venus-mars me-2"></i>
                                            الجنس *
                                        </label>
                                        <select class="form-select" id="gender" name="gender" required>
                                            <option value="">اختر الجنس</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- بيانات الاتصال -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-address-book"></i>
                                    بيانات الاتصال
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label fw-bold">
                                            <i class="fas fa-envelope me-2"></i>
                                            البريد الإلكتروني *
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="{{ old('email') }}" required>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label fw-bold">
                                            <i class="fas fa-phone me-2"></i>
                                            رقم الهاتف *
                                        </label>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               value="{{ old('phone') }}" required>
                                    </div>
                                    
                                    <div class="col-12 mb-3">
    <label for="province" class="form-label fw-bold">
        <i class="fas fa-map-marker-alt me-2"></i>
        المحافظة *
    </label>
    <select class="form-select" id="province" name="province" required>
        <option value="">اختر المحافظة</option>
        <option value="القاهرة" {{ old('province') == 'القاهرة' ? 'selected' : '' }}>القاهرة</option>
        <option value="الجيزة" {{ old('province') == 'الجيزة' ? 'selected' : '' }}>الجيزة</option>
        <option value="الإسكندرية" {{ old('province') == 'الإسكندرية' ? 'selected' : '' }}>الإسكندرية</option>
        <option value="الدقهلية" {{ old('province') == 'الدقهلية' ? 'selected' : '' }}>الدقهلية</option>
        <option value="البحر الأحمر" {{ old('province') == 'البحر الأحمر' ? 'selected' : '' }}>البحر الأحمر</option>
        <option value="البحيرة" {{ old('province') == 'البحيرة' ? 'selected' : '' }}>البحيرة</option>
        <option value="الفيوم" {{ old('province') == 'الفيوم' ? 'selected' : '' }}>الفيوم</option>
        <option value="الغربية" {{ old('province') == 'الغربية' ? 'selected' : '' }}>الغربية</option>
        <option value="الإسماعيلية" {{ old('province') == 'الإسماعيلية' ? 'selected' : '' }}>الإسماعيلية</option>
        <option value="المنوفية" {{ old('province') == 'المنوفية' ? 'selected' : '' }}>المنوفية</option>
        <option value="المنيا" {{ old('province') == 'المنيا' ? 'selected' : '' }}>المنيا</option>
        <option value="القليوبية" {{ old('province') == 'القليوبية' ? 'selected' : '' }}>القليوبية</option>
        <option value="الوادي الجديد" {{ old('province') == 'الوادي الجديد' ? 'selected' : '' }}>الوادي الجديد</option>
        <option value="السويس" {{ old('province') == 'السويس' ? 'selected' : '' }}>السويس</option>
        <option value="اسوان" {{ old('province') == 'اسوان' ? 'selected' : '' }}>اسوان</option>
        <option value="اسيوط" {{ old('province') == 'اسيوط' ? 'selected' : '' }}>اسيوط</option>
        <option value="بني سويف" {{ old('province') == 'بني سويف' ? 'selected' : '' }}>بني سويف</option>
        <option value="بورسعيد" {{ old('province') == 'بورسعيد' ? 'selected' : '' }}>بورسعيد</option>
        <option value="دمياط" {{ old('province') == 'دمياط' ? 'selected' : '' }}>دمياط</option>
        <option value="الشرقية" {{ old('province') == 'الشرقية' ? 'selected' : '' }}>الشرقية</option>
        <option value="جنوب سيناء" {{ old('province') == 'جنوب سيناء' ? 'selected' : '' }}>جنوب سيناء</option>
        <option value="كفر الشيخ" {{ old('province') == 'كفر الشيخ' ? 'selected' : '' }}>كفر الشيخ</option>
        <option value="مطروح" {{ old('province') == 'مطروح' ? 'selected' : '' }}>مطروح</option>
        <option value="الأقصر" {{ old('province') == 'الأقصر' ? 'selected' : '' }}>الأقصر</option>
        <option value="قنا" {{ old('province') == 'قنا' ? 'selected' : '' }}>قنا</option>
        <option value="شمال سيناء" {{ old('province') == 'شمال سيناء' ? 'selected' : '' }}>شمال سيناء</option>
        <option value="سوهاج" {{ old('province') == 'سوهاج' ? 'selected' : '' }}>سوهاج</option>
    </select>
</div>

                                </div>
                            </div>
                            
                            <!-- كلمة المرور -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-lock"></i>
                                    كلمة المرور
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label fw-bold">
                                            <i class="fas fa-key me-2"></i>
                                            كلمة المرور *
                                        </label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password" required>
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="password-strength">
                                            <div class="strength-bar">
                                                <div class="strength-fill" id="strengthFill"></div>
                                            </div>
                                            <small id="strengthText" class="text-muted">أدخل كلمة مرور قوية</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label fw-bold">
                                            <i class="fas fa-check-double me-2"></i>
                                            تأكيد كلمة المرور *
                                        </label>
                                        <input type="password" class="form-control" id="password_confirmation" 
                                               name="password_confirmation" required>
                                        <small id="passwordMatch" class="text-muted"></small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- الشروط والأحكام -->
                            <div class="form-section">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms">
                                        أوافق على <a href="#" class="back-link">الشروط والأحكام</a> 
                                        و <a href="#" class="back-link">سياسة الخصوصية</a> *
                                    </label>
                                </div>
                            </div>
                            
                            <!-- أزرار التحكم -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('auth.login') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    لديك حساب؟ سجل دخول
                                </a>
                                
                                <button type="submit" class="btn btn-register" id="submitBtn">
                                    <i class="fas fa-user-plus me-2"></i>
                                    إنشاء الحساب
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // تبديل إظهار/إخفاء كلمة المرور
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // فحص قوة كلمة المرور
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            let text = '';
            let className = '';
            
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            switch (strength) {
                case 0:
                case 1:
                case 2:
                    text = 'ضعيفة';
                    className = 'strength-weak';
                    strengthFill.style.width = '33%';
                    break;
                case 3:
                case 4:
                    text = 'متوسطة';
                    className = 'strength-medium';
                    strengthFill.style.width = '66%';
                    break;
                case 5:
                    text = 'قوية';
                    className = 'strength-strong';
                    strengthFill.style.width = '100%';
                    break;
            }
            
            strengthFill.className = 'strength-fill ' + className;
            strengthText.textContent = 'قوة كلمة المرور: ' + text;
        });
        
        // فحص تطابق كلمة المرور
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmation = this.value;
            const matchText = document.getElementById('passwordMatch');
            
            if (confirmation === '') {
                matchText.textContent = '';
                matchText.className = 'text-muted';
            } else if (password === confirmation) {
                matchText.textContent = 'كلمة المرور متطابقة ✓';
                matchText.className = 'text-success';
            } else {
                matchText.textContent = 'كلمة المرور غير متطابقة ✗';
                matchText.className = 'text-danger';
            }
        });
        
        // التحقق من العمر
        document.getElementById('date_of_birth').addEventListener('change', function() {
            const birthDate = new Date(this.value);
            const today = new Date();
            const age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            if (age < 18) {
                this.setCustomValidity('يجب أن تكون 18 سنة أو أكثر للتسجيل');
            } else {
                this.setCustomValidity('');
            }
        });
        
        // تأثيرات التحميل
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.register-container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                container.style.transition = 'all 0.5s ease';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        });
        
        // تحسين تجربة المستخدم
        document.getElementById('registerForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الإنشاء...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>

