<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام الانتخابات الإلكترونية المتكامل</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .hero-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            margin: 2rem 0;
            padding: 3rem;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .stats-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 2rem;
            margin: 1rem 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin: 1rem 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: none;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .btn-custom {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: white;
        }
        
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .text-gradient {
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .icon-large {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .counter {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
        }
    </style>
</head>
<body>
    <!-- شريط التنقل -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-gradient" href="{{ route('home') }}">
                <i class="fas fa-vote-yea me-2"></i>
                نظام الانتخابات الإلكترونية
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('voting.elections') }}">
                            <i class="fas fa-ballot-check me-1"></i>
                            الانتخابات المتاحة
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('verify_vote') }}">
                            <i class="fas fa-shield-check me-1"></i>
                            التحقق من الصوت
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>
                            تسجيل الدخول
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('auth.login') }}">تسجيل الدخول</a></li>
                            <li><a class="dropdown-item" href="{{ route('voter.register') }}">تسجيل ناخب جديد</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- المحتوى الرئيسي -->
    <div class="container" style="margin-top: 100px;">
        <!-- القسم الرئيسي -->
        <div class="hero-section text-center text-white">
            <h1 class="display-4 fw-bold mb-4">
                <i class="fas fa-vote-yea text-warning me-3"></i>
                نظام الانتخابات الإلكترونية المتكامل
            </h1>
            <p class="lead mb-4">
                نظام شامل ومتقدم للانتخابات الإلكترونية يجمع بين الأمان العالي والسهولة في الاستخدام
            </p>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <p class="mb-4">
                        يوفر النظام تجربة تصويت آمنة ومشفرة مع إمكانيات متقدمة للتقارير والإشعارات الذكية
                    </p>
                </div>
            </div>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('voting.elections') }}" class="btn btn-custom btn-lg">
                    <i class="fas fa-vote-yea me-2"></i>
                    عرض الانتخابات المتاحة
                </a>
                <a href="{{ route('voter.register') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-user-plus me-2"></i>
                    تسجيل ناخب جديد
                </a>
            </div>
        </div>

        <!-- إحصائيات النظام -->
        <div class="row mb-5">
            <div class="col-md-4">
                <div class="stats-card text-center">
                    <i class="fas fa-poll icon-large text-primary"></i>
                    <div class="counter">{{ $activeElections ?? 0 }}</div>
                    <h5>انتخابات نشطة</h5>
                    <p class="text-muted">انتخابات متاحة للتصويت حالياً</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card text-center">
                    <i class="fas fa-users icon-large text-success"></i>
                    <div class="counter">{{ number_format($totalVoters ?? 0) }}</div>
                    <h5>ناخب مسجل</h5>
                    <p class="text-muted">إجمالي الناخبين المسجلين في النظام</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card text-center">
                    <i class="fas fa-check-circle icon-large text-warning"></i>
                    <div class="counter">{{ number_format($totalVotes ?? 0) }}</div>
                    <h5>صوت مؤكد</h5>
                    <p class="text-muted">إجمالي الأصوات المؤكدة والمشفرة</p>
                </div>
            </div>
        </div>

        <!-- الميزات الرئيسية -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="text-white fw-bold">الميزات الرئيسية للنظام</h2>
                <p class="text-white-50">نظام متكامل يجمع بين التقنيات المتقدمة والأمان العالي</p>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="fas fa-shield-alt icon-large text-primary"></i>
                    <h4>أمان متقدم</h4>
                    <p>تشفير AES-256 + RSA-4096 لحماية الأصوات مع نظام تحقق مزدوج</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="fas fa-chart-line icon-large text-success"></i>
                    <h4>تقارير ذكية</h4>
                    <p>مخططات بيانية تفاعلية وتحليلات متقدمة بالذكاء الاصطناعي</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="fas fa-bell icon-large text-warning"></i>
                    <h4>إشعارات ذكية</h4>
                    <p>نظام إشعارات متقدم مع تخصيص المحتوى والتوقيت</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="fas fa-mobile-alt icon-large text-info"></i>
                    <h4>متوافق مع الجوال</h4>
                    <p>تصميم متجاوب يعمل على جميع الأجهزة والمتصفحات</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="fas fa-clock icon-large text-danger"></i>
                    <h4>نتائج فورية</h4>
                    <p>عرض النتائج والإحصائيات في الوقت الفعلي</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="fas fa-certificate icon-large text-secondary"></i>
                    <h4>شفافية كاملة</h4>
                    <p>إمكانية التحقق من صحة الأصوات وتتبع العملية الانتخابية</p>
                </div>
            </div>
        </div>

        <!-- دعوة للعمل -->
        <div class="hero-section text-center text-white">
            <h3 class="fw-bold mb-3">ابدأ رحلتك الانتخابية الآن</h3>
            <p class="mb-4">انضم إلى آلاف الناخبين الذين يثقون في نظامنا الآمن والموثوق</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('voter.register') }}" class="btn btn-custom btn-lg">
                    <i class="fas fa-user-plus me-2"></i>
                    سجل كناخب الآن
                </a>
                <a href="{{ route('verify_vote') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-search me-2"></i>
                    تحقق من صوتك
                </a>
            </div>
        </div>
    </div>

    <!-- تذييل الصفحة -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-vote-yea me-2"></i>نظام الانتخابات الإلكترونية</h5>
                    <p class="mb-0">نظام متكامل وآمن للانتخابات الإلكترونية</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <i class="fas fa-shield-check me-2"></i>
                        محمي بأعلى معايير الأمان
                    </p>
                    <small class="text-muted">الإصدار 2.0 المتكامل</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // تأثير العداد المتحرك
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current).toLocaleString('ar-EG');
            }, 20);
        }

        // تشغيل العدادات عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent.replace(/,/g, ''));
                animateCounter(counter, target);
            });
        });

        // تأثيرات التمرير
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            }
        });
    </script>
</body>
</html>

