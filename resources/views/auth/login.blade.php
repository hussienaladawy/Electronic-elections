<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام الانتخابات الإلكترونية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 1rem 0;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .login-form {
            padding: 2rem;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            padding: 12px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: white;
        }
        
        .user-type-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1rem;
            height: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .user-type-card:hover {
            border-color: #667eea;
            background-color: #f8f9ff;
        }
        
        .user-type-card.active {
            border-color: #667eea;
            background-color: #667eea;
            color: white;
        }
        
        .user-type-card input[type="radio"] {
            display: none;
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

        @media (max-width: 767.98px) {
            .login-header {
                padding: 1.5rem;
            }
            .login-form {
                padding: 1.5rem;
            }
            .user-type-card {
                padding: 0.75rem;
            }
            .user-type-card .fa-2x {
                font-size: 1.5em;
            }
            .user-type-card h6 {
                font-size: 0.9rem;
            }
            .user-type-card small {
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7 col-xl-6">
                <div class="login-container">
                    <!-- رأس النموذج -->
                    <div class="login-header">
                        <h2 class="mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            تسجيل الدخول
                        </h2>
                        <p class="mb-0">ادخل إلى نظام الانتخابات الإلكترونية</p>
                    </div>
                    
                    <!-- نموذج تسجيل الدخول -->
                    <div class="login-form">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif
                        
                        @if (session('success'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('auth.login.submit') }}">
                            @csrf
                            
                            <!-- نوع المستخدم -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-user-tag me-2"></i>
                                    نوع المستخدم
                                </label>
                                
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="user-type-card" for="voter">
                                            <input type="radio" name="user_type" value="voter" id="voter" 
                                                   {{ old('user_type', 'voter') == 'voter' ? 'checked' : '' }}>
                                            <div class="text-center">
                                                <i class="fas fa-user fa-2x mb-2"></i>
                                                <h6 class="mb-1">ناخب</h6>
                                                <small>للمشاركة في التصويت</small>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <div class="col-6">
                                        <label class="user-type-card" for="assistant">
                                            <input type="radio" name="user_type" value="assistant" id="assistant"
                                                   {{ old('user_type') == 'assistant' ? 'checked' : '' }}>
                                            <div class="text-center">
                                                <i class="fas fa-user-friends fa-2x mb-2"></i>
                                                <h6 class="mb-1">مساعد</h6>
                                                <small>مساعد إداري</small>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <div class="col-6">
                                        <label class="user-type-card" for="admin">
                                            <input type="radio" name="user_type" value="admin" id="admin"
                                                   {{ old('user_type') == 'admin' ? 'checked' : '' }}>
                                            <div class="text-center">
                                                <i class="fas fa-user-shield fa-2x mb-2"></i>
                                                <h6 class="mb-1">أدمن</h6>
                                                <small>مدير النظام</small>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <div class="col-6">
                                        <label class="user-type-card" for="super_admin">
                                            <input type="radio" name="user_type" value="super_admin" id="super_admin"
                                                   {{ old('user_type') == 'super_admin' ? 'checked' : '' }}>
                                            <div class="text-center">
                                                <i class="fas fa-user-crown fa-2x mb-2"></i>
                                                <h6 class="mb-1">سوبرادمن</h6>
                                                <small>مدير عام</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- البريد الإلكتروني -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">
                                    <i class="fas fa-envelope me-2"></i>
                                    البريد الإلكتروني
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email') }}" required>
                            </div>
                            
                            <!-- كلمة المرور -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">
                                    <i class="fas fa-lock me-2"></i>
                                    كلمة المرور
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- تذكرني -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">
                                        تذكرني
                                    </label>
                                </div>
                            </div>
                            
                            <!-- زر تسجيل الدخول -->
                            <button type="submit" class="btn btn-login w-100 mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                تسجيل الدخول
                            </button>
                            
                            <!-- روابط إضافية -->
                            <div class="text-center">
                                <p class="mb-2">
                                    <a href="{{ route('home') }}" class="back-link">
                                        <i class="fas fa-arrow-right me-1"></i>
                                        العودة للصفحة الرئيسية
                                    </a>
                                </p>
                                <p class="mb-0">
                                    ليس لديك حساب؟ 
                                    <a href="{{ route('voter.register') }}" class="back-link">
                                        سجل كناخب جديد
                                    </a>
                                </p>
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
        
        // تفعيل بطاقات نوع المستخدم
        document.querySelectorAll('input[name="user_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // إزالة التفعيل من جميع البطاقات
                document.querySelectorAll('.user-type-card').forEach(card => {
                    card.classList.remove('active');
                });
                
                // تفعيل البطاقة المحددة
                this.closest('.user-type-card').classList.add('active');
            });
        });
        
        // تفعيل البطاقة المحددة مسبقاً
        document.addEventListener('DOMContentLoaded', function() {
            const checkedRadio = document.querySelector('input[name="user_type"]:checked');
            if (checkedRadio) {
                checkedRadio.closest('.user-type-card').classList.add('active');
            }
        });
        
        // تأثيرات التحميل
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.login-container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                container.style.transition = 'all 0.5s ease';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>

