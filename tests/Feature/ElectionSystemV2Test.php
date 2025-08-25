<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\Voter;
use App\Models\SuperAdmin;
use App\Models\Notification;
use Carbon\Carbon;

class ElectionSystemV2Test extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $superAdmin;
    protected $voter;
    protected $election;
    protected $candidate;

    protected function setUp(): void
    {
        parent::setUp();
        
        // إنشاء بيانات اختبار أساسية
        $this->superAdmin = SuperAdmin::factory()->create();
        $this->voter = Voter::factory()->create();
        $this->election = Election::factory()->create([
            'status' => 'active',
            'start_date' => Carbon::now()->subHour(),
            'end_date' => Carbon::now()->addHour(),
            'created_by' => $this->superAdmin->id
        ]);
        $this->candidate = Candidate::factory()->create([
            'election_id' => $this->election->id,
            'status' => true
        ]);
    }

    // ==================== اختبارات التصويت الإلكتروني ====================

    /** @test */
    public function voter_can_view_available_elections()
    {
        $response = $this->get(route('voting.elections'));
        
        $response->assertStatus(200);
        $response->assertSee($this->election->title);
    }

    /** @test */
    public function voter_can_access_voting_page_for_active_election()
    {
        $response = $this->get(route('voting.vote', $this->election));
        
        $response->assertStatus(200);
        $response->assertSee($this->candidate->name);
    }

    /** @test */
    public function authenticated_voter_can_submit_vote()
    {
        $this->actingAs($this->voter, 'voter');
        
        $response = $this->post(route('voting.submit', $this->election), [
            'candidate_id' => $this->candidate->id,
            'voter_password' => 'password' // كلمة المرور الافتراضية للمصنع
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('votes', [
            'election_id' => $this->election->id,
            'candidate_id' => $this->candidate->id,
            'voter_id' => $this->voter->id
        ]);
    }

    /** @test */
    public function voter_cannot_vote_twice_in_same_election()
    {
        $this->actingAs($this->voter, 'voter');
        
        // التصويت الأول
        Vote::create([
            'election_id' => $this->election->id,
            'candidate_id' => $this->candidate->id,
            'voter_id' => $this->voter->id,
            'is_verified' => true,
            'voted_at' => now()
        ]);
        
        // محاولة التصويت مرة أخرى
        $response = $this->post(route('voting.submit', $this->election), [
            'candidate_id' => $this->candidate->id,
            'voter_password' => 'password'
        ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /** @test */
    public function vote_is_encrypted_and_can_be_verified()
    {
        $this->actingAs($this->voter, 'voter');
        
        $this->post(route('voting.submit', $this->election), [
            'candidate_id' => $this->candidate->id,
            'voter_password' => 'password'
        ]);
        
        $vote = Vote::where('voter_id', $this->voter->id)->first();
        
        $this->assertNotNull($vote->encrypted_vote);
        $this->assertNotNull($vote->vote_hash);
        $this->assertTrue($vote->validateVote());
    }

    /** @test */
    public function vote_verification_works_correctly()
    {
        $vote = Vote::create([
            'election_id' => $this->election->id,
            'candidate_id' => $this->candidate->id,
            'voter_id' => $this->voter->id,
            'is_verified' => true,
            'voted_at' => now()
        ]);
        
        $response = $this->post(route('voting.verify'), [
            'vote_hash' => $vote->vote_hash,
            'verification_code' => $vote->verification_code
        ]);
        
        $response->assertJson(['success' => true]);
    }

    // ==================== اختبارات إدارة الانتخابات ====================

    /** @test */
    public function super_admin_can_create_election()
    {
        $this->actingAs($this->superAdmin, 'super_admin');
        
        $electionData = [
            'title' => 'انتخابات اختبار',
            'description' => 'وصف الانتخابات',
            'type' => 'local',
            'start_date' => Carbon::now()->addDay()->toDateTimeString(),
            'end_date' => Carbon::now()->addDays(2)->toDateTimeString(),
            'registration_start' => Carbon::now()->toDateTimeString(),
            'registration_end' => Carbon::now()->addHours(12)->toDateTimeString()
        ];
        
        $response = $this->post(route('super_admin.elections.store'), $electionData);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('elections', [
            'title' => 'انتخابات اختبار',
            'type' => 'local'
        ]);
    }

    /** @test */
    public function super_admin_can_add_candidate_to_election()
    {
        $this->actingAs($this->superAdmin, 'super_admin');
        
        $candidateData = [
            'name' => 'مرشح اختبار',
            'biography' => 'سيرة ذاتية للمرشح',
            'party_affiliation' => 'حزب اختبار',
            'order_number' => 1,
            'status' => true
        ];
        
        $response = $this->post(
            route('super_admin.elections.candidates.store', $this->election), 
            $candidateData
        );
        
        $response->assertRedirect();
        $this->assertDatabaseHas('candidates', [
            'name' => 'مرشح اختبار',
            'election_id' => $this->election->id
        ]);
    }

    /** @test */
    public function election_can_be_activated_with_sufficient_candidates()
    {
        $this->actingAs($this->superAdmin, 'super_admin');
        
        // إضافة مرشح ثاني
        Candidate::factory()->create([
            'election_id' => $this->election->id,
            'status' => true
        ]);
        
        $response = $this->post(route('super_admin.elections.activate', $this->election));
        
        $response->assertRedirect();
        $this->assertDatabaseHas('elections', [
            'id' => $this->election->id,
            'status' => 'active'
        ]);
    }

    // ==================== اختبارات التقارير والمخططات ====================

    /** @test */
    public function super_admin_can_access_reports_dashboard()
    {
        $this->actingAs($this->superAdmin, 'super_admin');
        
        $response = $this->get(route('super_admin.reports.dashboard'));
        
        $response->assertStatus(200);
        $response->assertViewHas(['stats', 'recentElections']);
    }

    /** @test */
    public function election_results_report_shows_correct_data()
    {
        $this->actingAs($this->superAdmin, 'super_admin');
        
        // إضافة بعض الأصوات
        Vote::factory()->count(5)->create([
            'election_id' => $this->election->id,
            'candidate_id' => $this->candidate->id,
            'is_verified' => true
        ]);
        
        $response = $this->get(route('super_admin.reports.election.results', $this->election));
        
        $response->assertStatus(200);
        $response->assertViewHas(['candidateResults', 'totalVotes']);
    }

    /** @test */
    public function chart_data_api_returns_correct_format()
    {
        $this->actingAs($this->superAdmin, 'super_admin');
        
        Vote::factory()->count(3)->create([
            'election_id' => $this->election->id,
            'candidate_id' => $this->candidate->id,
            'is_verified' => true
        ]);
        
        $response = $this->get(route('super_admin.reports.chart.data'), [
            'type' => 'candidate_votes',
            'election_id' => $this->election->id
        ]);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['name', 'votes', 'percentage']
        ]);
    }

    /** @test */
    public function reports_can_be_exported_as_csv()
    {
        $this->actingAs($this->superAdmin, 'super_admin');
        
        $response = $this->get(route('super_admin.reports.export'), [
            'type' => 'election_results',
            'election_id' => $this->election->id,
            'format' => 'csv'
        ]);
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    // ==================== اختبارات نظام الإشعارات ====================

    /** @test */
    public function super_admin_can_create_notification()
    {
        $this->actingAs($this->superAdmin, 'super_admin');
        
        $notificationData = [
            'title' => 'إشعار اختبار',
            'message' => 'محتوى الإشعار',
            'type' => 'announcement',
            'priority' => 'normal',
            'channels' => ['in_app', 'email'],
            'target_audience' => [
                ['type' => 'all_voters']
            ]
        ];
        
        $response = $this->post(route('super_admin.notifications.store'), $notificationData);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('notifications', [
            'title' => 'إشعار اختبار',
            'type' => 'announcement'
        ]);
    }

    /** @test */
    public function notification_determines_recipients_correctly()
    {
        $notification = Notification::create([
            'title' => 'إشعار اختبار',
            'message' => 'محتوى الإشعار',
            'type' => 'announcement',
            'priority' => 'normal',
            'channels' => ['in_app'],
            'target_audience' => [
                ['type' => 'all_voters']
            ],
            'status' => 'draft',
            'created_by' => $this->superAdmin->id
        ]);
        
        $recipients = $notification->determineRecipients();
        
        $this->assertGreaterThan(0, $recipients->count());
        $this->assertTrue($recipients->contains('id', $this->voter->id));
    }

    // /** @test */
    // public function notification_can_be_sent_immediately()
    // {
    //     $this->actingAs($this->superAdmin, 'super_admin');
        
    //     $notification = Notification::create([
    //         'title' => 'إشعار فوري',
    //         'message' => 'محتوى الإشعار',
    //         'type' => 'alert',
    //         'priority' => 'urgent',
    //         'channels' => ['in_app'],
    //         'target_audience' => [
    //             ['type' => 'all_voters']
    //         ],
    //         'status' => 'pending',
    //         'created_by' => $this->superAdmin->id
    //     ]);
        
    //     // إضافة مستلم
    //     // NotificationRecipient::create([
    //     //     'notification_id' => $notification->id,
    //     //     'recipient_type' => Voter::class,
    //     //     'recipient_id' => $this->voter->id,
    //     //     'status' => 'pending'
    //     // ]);
        
    //     $response = $this->post(route('super_admin.notifications.send', $notification));
        
    //     $response->assertRedirect();
    //     $this->assertDatabaseHas('notifications', [
    //         'id' => $notification->id,
    //         'status' => 'sent'
    //     ]);
    // }

    // /** @test */
    // public function scheduled_notification_can_be_processed()
    // {
    //     $notification = Notification::create([
    //         'title' => 'إشعار مجدول',
    //         'message' => 'محتوى الإشعار',
    //         'type' => 'reminder',
    //         'priority' => 'normal',
    //         'channels' => ['in_app'],
    //         'target_audience' => [
    //             ['type' => 'all_voters']
    //         ],
    //         'status' => 'scheduled',
    //         'scheduled_at' => Carbon::now()->subMinute(),
    //         'created_by' => $this->superAdmin->id
    //     ]);
        
    //     // إضافة مستلم
    //     // NotificationRecipient::create([
    //     //     'notification_id' => $notification->id,
    //     //     'recipient_type' => Voter::class,
    //     //     'recipient_id' => $this->voter->id,
    //     //     'status' => 'pending'
    //     // ]);
        
    //     $response = $this->post(route('api.v2.notifications.process_scheduled'));
        
    //     $response->assertStatus(200);
    //     $this->assertDatabaseHas('notifications', [
    //         'id' => $notification->id,
    //         'status' => 'sent'
    //     ]);
    // }

    // /** @test */
    // public function notification_delivery_tracking_works()
    // {
    //     $notification = Notification::create([
    //         'title' => 'إشعار تتبع',
    //         'message' => 'محتوى الإشعار',
    //         'type' => 'announcement',
    //         'priority' => 'normal',
    //         'channels' => ['in_app'],
    //         'target_audience' => [
    //             ['type' => 'all_voters']
    //         ],
    //         'status' => 'sent',
    //         'created_by' => $this->superAdmin->id
    //     ]);
        
    //     // $recipient = NotificationRecipient::create([
    //     //     'notification_id' => $notification->id,
    //     //     'recipient_type' => Voter::class,
    //     //     'recipient_id' => $this->voter->id,
    //     //     'status' => 'delivered'
    //     // ]);
        
    //     // $delivery = NotificationDelivery::create([
    //     //     'notification_id' => $notification->id,
    //     //     'recipient_id' => $recipient->id,
    //     //     'channel' => 'in_app',
    //     //     'status' => 'delivered',
    //     //     'delivered_at' => now()
    //     // ]);
        
    //     // $this->assertTrue($delivery->isDelivered());
    //     // $this->assertNotNull($delivery->delivered_at);
    // }

    // ==================== اختبارات التكامل ====================

    /** @test */
    public function complete_voting_workflow_works()
    {
        // 1. إنشاء انتخابات
        $this->actingAs($this->superAdmin, 'super_admin');
        
        $election = Election::create([
            'title' => 'انتخابات تكامل',
            'type' => 'local',
            'start_date' => Carbon::now()->subHour(),
            'end_date' => Carbon::now()->addHour(),
            'registration_start' => Carbon::now()->subDay(),
            'registration_end' => Carbon::now()->subHour(),
            'status' => 'active',
            'created_by' => $this->superAdmin->id
        ]);
        
        // 2. إضافة مرشحين
        $candidate1 = Candidate::create([
            'election_id' => $election->id,
            'name' => 'المرشح الأول',
            'order_number' => 1,
            'status' => true,
            'created_by' => $this->superAdmin->id
        ]);
        
        $candidate2 = Candidate::create([
            'election_id' => $election->id,
            'name' => 'المرشح الثاني',
            'order_number' => 2,
            'status' => true,
            'created_by' => $this->superAdmin->id
        ]);
        
        // 3. التصويت
        $this->actingAs($this->voter, 'voter');
        
        $response = $this->post(route('voting.submit', $election), [
            'candidate_id' => $candidate1->id,
            'voter_password' => 'password'
        ]);
        
        $response->assertRedirect();
        
        // 4. التحقق من النتائج
        $this->actingAs($this->superAdmin, 'super_admin');
        
        $response = $this->get(route('super_admin.reports.election.results', $election));
        $response->assertStatus(200);
        
        // 5. إرسال إشعار بالنتائج
        // $notification = Notification::create([
        //     'title' => 'نتائج الانتخابات',
        //     'message' => 'تم الانتهاء من عملية التصويت',
        //     'type' => 'announcement',
        //     'priority' => 'high',
        //     'channels' => ['in_app'],
        //     'target_audience' => [
        //         ['type' => 'all_voters']
        //     ],
        //     'status' => 'pending',
        //     'created_by' => $this->superAdmin->id
        // ]);
        
        // // NotificationRecipient::create([
        // //     'notification_id' => $notification->id,
        // //     'recipient_type' => Voter::class,
        // //     'recipient_id' => $this->voter->id,
        // //     'status' => 'pending'
        // // ]);
        
        // $response = $this->post(route('super_admin.notifications.send', $notification));
        // $response->assertRedirect();
        
        // التحقق من التكامل الكامل
        $this->assertDatabaseHas('votes', [
            'election_id' => $election->id,
            'candidate_id' => $candidate1->id,
            'voter_id' => $this->voter->id
        ]);
        
        // $this->assertDatabaseHas('notifications', [
        //     'id' => $notification->id,
        //     'status' => 'sent'
        // ]);
    }

    /** @test */
    public function system_health_check_works()
    {
        $response = $this->get(route('health.check'));
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'timestamp',
            'checks' => [
                'database',
                'voting_system',
                'notification_system',
                'reporting_system'
            ]
        ]);
    }

    /** @test */
    public function system_statistics_are_accurate()
    {
        // إضافة بيانات اختبار
        Vote::factory()->count(5)->create([
            'election_id' => $this->election->id,
            'is_verified' => true
        ]);
        
        Notification::factory()->count(3)->create([
            'status' => 'sent',
            'created_by' => $this->superAdmin->id
        ]);
        
        $response = $this->get(route('health.stats'));
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'elections' => ['total', 'active', 'completed'],
            'votes' => ['total', 'verified', 'today'],
            'notifications' => ['total', 'sent', 'scheduled'],
            'users' => ['voters', 'admins']
        ]);
        
        $data = $response->json();
        $this->assertGreaterThanOrEqual(5, $data['votes']['total']);
        $this->assertGreaterThanOrEqual(3, $data['notifications']['total']);
    }
}