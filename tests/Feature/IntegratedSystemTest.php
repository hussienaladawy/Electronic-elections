<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\SuperAdmin;
use App\Models\Admin;
use App\Models\Assistant;
use App\Models\Voter;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;

class IntegratedSystemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // إنشاء بيانات اختبار أساسية
        $this->createTestUsers();
        $this->createTestElection();
    }

    private function createTestUsers()
    {
        // إنشاء سوبرادمن للاختبار
        $this->superAdmin = SuperAdmin::create([
            'name' => 'Super Admin Test',
            'email' => 'superadmin@test.com',
            'password' => Hash::make('password123'),
            'status' => true
        ]);

        // إنشاء أدمن للاختبار
        $this->admin = Admin::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'status' => true
        ]);

        // إنشاء مساعد للاختبار
        $this->assistant = Assistant::create([
            'name' => 'Assistant Test',
            'email' => 'assistant@test.com',
            'password' => Hash::make('password123'),
            'status' => true
        ]);

        // إنشاء ناخب للاختبار
        $this->voter = Voter::create([
            'name' => 'Voter Test',
            'email' => 'voter@test.com',
            'national_id' => '1234567890',
            'phone' => '0501234567',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'province' => 'الرياض',
            'password' => Hash::make('password123'),
            'status' => true
        ]);
    }

    private function createTestElection()
    {
        // إنشاء انتخابات للاختبار
        $this->election = Election::create([
            'name' => 'انتخابات اختبار',
            'description' => 'انتخابات للاختبار',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'status' => 'active',
            'security_settings' => [
                'encryption_enabled' => true,
                'verification_required' => true
            ],
            'requires_verification' => true,
            'max_votes_per_ip' => 1,
            'allow_vote_verification' => true
        ]);

        // إنشاء مرشحين للاختبار
        $this->candidate1 = Candidate::create([
            'election_id' => $this->election->id,
            'name' => 'مرشح أول',
            'description' => 'وصف المرشح الأول',
            'biography' => 'السيرة الذاتية للمرشح الأول',
            'display_order' => 1
        ]);

        $this->candidate2 = Candidate::create([
            'election_id' => $this->election->id,
            'name' => 'مرشح ثاني',
            'description' => 'وصف المرشح الثاني',
            'biography' => 'السيرة الذاتية للمرشح الثاني',
            'display_order' => 2
        ]);
    }

    /** @test */
    public function test_home_page_loads_successfully()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertSee('نظام الانتخابات الإلكترونية المتكامل');
        $response->assertViewIs('welcome');
    }

    /** @test */
    public function test_login_page_loads_successfully()
    {
        $response = $this->get('/auth/login');
        
        $response->assertStatus(200);
        $response->assertSee('تسجيل الدخول');
        $response->assertViewIs('auth.login');
    }

    /** @test */
    public function test_voter_registration_page_loads_successfully()
    {
        $response = $this->get('/voter/register');
        
        $response->assertStatus(200);
        $response->assertSee('تسجيل ناخب جديد');
        $response->assertViewIs('voter.register');
    }

    /** @test */
    public function test_super_admin_login_works()
    {
        $response = $this->post('/auth/login', [
            'email' => 'superadmin@test.com',
            'password' => 'password123',
            'user_type' => 'super_admin'
        ]);

        $response->assertRedirect('/super_admin/dashboard');
        $this->assertAuthenticatedAs($this->superAdmin, 'super_admin');
    }

    /** @test */
    public function test_admin_login_works()
    {
        $response = $this->post('/auth/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
            'user_type' => 'admin'
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($this->admin, 'admin');
    }

    /** @test */
    public function test_assistant_login_works()
    {
        $response = $this->post('/auth/login', [
            'email' => 'assistant@test.com',
            'password' => 'password123',
            'user_type' => 'assistant'
        ]);

        $response->assertRedirect('/assistant/dashboard');
        $this->assertAuthenticatedAs($this->assistant, 'assistant');
    }

    /** @test */
    public function test_voter_login_works()
    {
        $response = $this->post('/auth/login', [
            'email' => 'voter@test.com',
            'password' => 'password123',
            'user_type' => 'voter'
        ]);

        $response->assertRedirect('/voter/dashboard');
        $this->assertAuthenticatedAs($this->voter, 'voter');
    }

    /** @test */
    public function test_voter_registration_works()
    {
        $voterData = [
            'name' => 'ناخب جديد',
            'email' => 'newvoter@test.com',
            'national_id' => '9876543210',
            'phone' => '0509876543',
            'date_of_birth' => '1995-05-15',
            'gender' => 'female',
            'province' => 'مكة المكرمة',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1'
        ];

        $response = $this->post('/voter/register', $voterData);

        $response->assertRedirect('/auth/login');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('voters', [
            'email' => 'newvoter@test.com',
            'national_id' => '9876543210'
        ]);
    }

    /** @test */
    public function test_super_admin_can_access_dashboard()
    {
        $this->actingAs($this->superAdmin, 'super_admin');
        
        $response = $this->get('/super_admin/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('super_admin.dashboard');
    }

    /** @test */
    public function test_super_admin_can_manage_voters()
    {
        $this->actingAs($this->superAdmin, 'super_admin');
        
        // اختبار عرض قائمة الناخبين
        $response = $this->get('/super_admin/voters');
        $response->assertStatus(200);
        
        // اختبار إنشاء ناخب جديد
        $voterData = [
            'name' => 'ناخب من السوبرادمن',
            'email' => 'superadmin_voter@test.com',
            'national_id' => '1111111111',
            'phone' => '0501111111',
            'date_of_birth' => '1988-12-25',
            'gender' => 'male',
            'province' => 'الدمام',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];
        
        $response = $this->post('/super_admin/voters', $voterData);
        $response->assertRedirect();
        
        $this->assertDatabaseHas('voters', [
            'email' => 'superadmin_voter@test.com'
        ]);
    }

    /** @test */
    public function test_voting_system_works()
    {
        $this->actingAs($this->voter, 'voter');
        
        // اختبار عرض صفحة التصويت
        $response = $this->get("/voting/election/{$this->election->id}");
        $response->assertStatus(200);
        
        // اختبار إرسال الصوت
        $response = $this->post("/voting/election/{$this->election->id}/vote", [
            'candidate_id' => $this->candidate1->id
        ]);
        
        $response->assertRedirect();
        
        // التحقق من حفظ الصوت
        $this->assertDatabaseHas('votes', [
            'election_id' => $this->election->id,
            'voter_id' => $this->voter->id,
            'candidate_id' => $this->candidate1->id
        ]);
    }

    /** @test */
    public function test_available_elections_page_works()
    {
        $response = $this->get('/voting/elections');
        
        $response->assertStatus(200);
        $response->assertSee($this->election->name);
        $response->assertViewIs('voting.elections');
    }

    /** @test */
    public function test_vote_verification_works()
    {
        // إنشاء صوت للاختبار
        $vote = Vote::create([
            'election_id' => $this->election->id,
            'voter_id' => $this->voter->id,
            'candidate_id' => $this->candidate1->id,
            'vote_hash' => 'test_hash_123',
            'encrypted_vote' => 'encrypted_data',
            'verification_code' => 'VERIFY123456789ABCDE',
            'is_verified' => true,
            'voted_at' => now(),
            'verified_at' => now()
        ]);
        
        $response = $this->post('/public/verify-vote', [
            'verification_code' => 'VERIFY123456789ABCDE'
        ]);
        
        $response->assertStatus(200);
        $response->assertViewIs('public.vote_verification');
    }

    /** @test */
    public function test_notification_system_works()
    {
        $this->actingAs($this->superAdmin, 'super_admin');
        
        // اختبار إنشاء إشعار
        $notificationData = [
            'title' => 'إشعار اختبار',
            'message' => 'هذا إشعار للاختبار',
            'type' => 'announcement',
            'priority' => 'normal',
            'target_audience' => ['voters'],
            'channels' => ['in_app', 'email']
        ];
        
        $response = $this->post('/super_admin/notifications', $notificationData);
        $response->assertRedirect();
        
        $this->assertDatabaseHas('notifications', [
            'title' => 'إشعار اختبار'
        ]);
    }

    /** @test */
    public function test_api_system_stats_works()
    {
        $response = $this->get('/api/v1/system/stats');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'elections' => ['total', 'active', 'completed', 'upcoming'],
            'votes' => ['total', 'verified', 'today', 'this_week'],
            'users' => ['voters', 'super_admins', 'admins', 'assistants'],
            'notifications' => ['total', 'sent', 'scheduled', 'pending']
        ]);
    }

    /** @test */
    public function test_health_check_works()
    {
        $response = $this->get('/api/v1/health/check');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'timestamp',
            'checks'
        ]);
    }

    /** @test */
    public function test_unauthorized_access_is_blocked()
    {
        // اختبار منع الوصول غير المصرح به للسوبرادمن
        $response = $this->get('/super_admin/dashboard');
        $response->assertRedirect('/auth/login');
        
        // اختبار منع الوصول غير المصرح به للأدمن
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/auth/login');
        
        // اختبار منع الوصول غير المصرح به للمساعد
        $response = $this->get('/assistant/dashboard');
        $response->assertRedirect('/auth/login');
        
        // اختبار منع الوصول غير المصرح به للناخب
        $response = $this->get('/voter/dashboard');
        $response->assertRedirect('/auth/login');
    }

    /** @test */
    public function test_cross_user_type_access_is_blocked()
    {
        // اختبار منع وصول الناخب لصفحات السوبرادمن
        $this->actingAs($this->voter, 'voter');
        $response = $this->get('/super_admin/dashboard');
        $response->assertRedirect('/auth/login');
        
        // اختبار منع وصول المساعد لصفحات السوبرادمن
        $this->actingAs($this->assistant, 'assistant');
        $response = $this->get('/super_admin/dashboard');
        $response->assertRedirect('/auth/login');
        
        // اختبار منع وصول الأدمن لصفحات السوبرادمن
        $this->actingAs($this->admin, 'admin');
        $response = $this->get('/super_admin/dashboard');
        $response->assertRedirect('/auth/login');
    }

    /** @test */
    public function test_logout_works_for_all_user_types()
    {
        // اختبار تسجيل خروج السوبرادمن
        $this->actingAs($this->superAdmin, 'super_admin');
        $response = $this->post('/auth/logout');
        $response->assertRedirect('/');
        $this->assertGuest('super_admin');
        
        // اختبار تسجيل خروج الأدمن
        $this->actingAs($this->admin, 'admin');
        $response = $this->post('/auth/logout');
        $response->assertRedirect('/');
        $this->assertGuest('admin');
        
        // اختبار تسجيل خروج المساعد
        $this->actingAs($this->assistant, 'assistant');
        $response = $this->post('/auth/logout');
        $response->assertRedirect('/');
        $this->assertGuest('assistant');
        
        // اختبار تسجيل خروج الناخب
        $this->actingAs($this->voter, 'voter');
        $response = $this->post('/auth/logout');
        $response->assertRedirect('/');
        $this->assertGuest('voter');
    }

    /** @test */
    public function test_database_integrity()
    {
        // التحقق من وجود الجداول الأساسية
        $this->assertDatabaseHas('super_admins', ['id' => $this->superAdmin->id]);
        $this->assertDatabaseHas('admins', ['id' => $this->admin->id]);
        $this->assertDatabaseHas('assistants', ['id' => $this->assistant->id]);
        $this->assertDatabaseHas('voters', ['id' => $this->voter->id]);
        $this->assertDatabaseHas('elections', ['id' => $this->election->id]);
        $this->assertDatabaseHas('candidates', ['id' => $this->candidate1->id]);
        
        // التحقق من العلاقات
        $this->assertEquals(2, $this->election->candidates()->count());
        $this->assertEquals($this->election->id, $this->candidate1->election_id);
    }

    /** @test */
    public function test_security_features()
    {
        // اختبار تشفير كلمات المرور
        $this->assertTrue(Hash::check('password123', $this->superAdmin->password));
        $this->assertTrue(Hash::check('password123', $this->voter->password));
        
        // اختبار فريدية البيانات
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Voter::create([
            'name' => 'ناخب مكرر',
            'email' => 'voter@test.com', // نفس البريد الإلكتروني
            'national_id' => '9999999999',
            'phone' => '0509999999',
            'date_of_birth' => '1992-01-01',
            'gender' => 'male',
            'province' => 'الرياض',
            'password' => Hash::make('password123'),
            'status' => true
        ]);
    }

    /** @test */
    public function test_performance_and_scalability()
    {
        // اختبار إنشاء عدد كبير من الناخبين
        $startTime = microtime(true);
        
        for ($i = 0; $i < 100; $i++) {
            Voter::create([
                'name' => "ناخب $i",
                'email' => "voter$i@test.com",
                'national_id' => str_pad($i, 10, '0', STR_PAD_LEFT),
                'phone' => '050' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'date_of_birth' => '1990-01-01',
                'gender' => $i % 2 == 0 ? 'male' : 'female',
                'province' => 'الرياض',
                'password' => Hash::make('password123'),
                'status' => true
            ]);
        }
        
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        
        // يجب أن يكون الوقت أقل من 10 ثواني
        $this->assertLessThan(10, $executionTime);
        
        // التحقق من إنشاء البيانات
        $this->assertEquals(101, Voter::count()); // 100 + الناخب الأصلي
    }
}

