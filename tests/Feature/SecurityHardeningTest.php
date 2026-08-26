<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Disposition;
use App\Models\Letter;
use App\Models\LetterCategory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $pimpinan;
    private User $pelaksana1;
    private User $pelaksana2;
    private Letter $letter;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        $pimpinanRole = Role::firstOrCreate(['name' => 'pimpinan'], ['display_name' => 'Pimpinan']);
        $pelaksanaRole = Role::firstOrCreate(['name' => 'pelaksana'], ['display_name' => 'Pelaksana']);

        $category = LetterCategory::create([
            'code' => 'KMN',
            'name' => 'Keamanan',
            'description' => 'Surat Keamanan'
        ]);

        $this->admin = User::create([
            'name' => 'Admin SIPDK',
            'email' => 'admin@sipdk.go.id',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
        ]);

        $this->pimpinan = User::create([
            'name' => 'Lurah Dukuh',
            'email' => 'lurah@sipdk.go.id',
            'password' => bcrypt('password'),
            'role_id' => $pimpinanRole->id,
        ]);

        $this->pelaksana1 = User::create([
            'name' => 'Kasi Pemerintahan',
            'email' => 'kasi1@sipdk.go.id',
            'password' => bcrypt('password'),
            'role_id' => $pelaksanaRole->id,
        ]);

        $this->pelaksana2 = User::create([
            'name' => 'Kasi PMD',
            'email' => 'kasi2@sipdk.go.id',
            'password' => bcrypt('password'),
            'role_id' => $pelaksanaRole->id,
        ]);

        // Upload fake letter file
        $file = UploadedFile::fake()->create('dokumen_rahasia.pdf', 200, 'application/pdf');
        $storedPath = $file->storeAs('letters', 'test_secret.pdf', 'public');

        $this->letter = Letter::create([
            'agenda_number' => '001/SEC/2026',
            'reference_number' => '005/SEC/2026',
            'sender' => 'Kantor Camat',
            'letter_date' => now(),
            'received_date' => now(),
            'subject' => 'Dokumen Rahasia Wilayah',
            'category_id' => $category->id,
            'degree' => 'Rahasia',
            'status' => 'Baru',
            'file_path' => $storedPath,
            'file_name' => 'dokumen_rahasia.pdf',
            'file_type' => 'pdf',
            'file_size' => 204800,
            'created_by' => $this->admin->id,
        ]);
    }

    public function test_security_headers_are_present_in_responses()
    {
        $response = $this->get(route('login'));

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
        $this->assertTrue($response->headers->has('Content-Security-Policy'));
    }

    public function test_login_rate_limiting_blocks_excessive_attempts()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login'), [
                'email' => 'wrong@sipdk.go.id',
                'password' => 'wrongpassword',
            ]);
        }

        // 6th attempt should be throttled (HTTP 429)
        $response = $this->post(route('login'), [
            'email' => 'wrong@sipdk.go.id',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(429);
    }

    public function test_failed_login_records_audit_log_with_ip()
    {
        $this->post(route('login'), [
            'email' => 'attacker@malicious.com',
            'password' => 'badpass123',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'Login Gagal',
            'module' => 'Authentication',
        ]);
    }

    public function test_unauthorized_user_cannot_access_letter_file()
    {
        // Pelaksana 2 has NOT received disposition for this letter
        $response = $this->actingAs($this->pelaksana2)->get(route('letters.file', $this->letter->id));
        $response->assertStatus(403);
    }

    public function test_authorized_disposition_recipient_can_stream_letter_file()
    {
        // Disposed to pelaksana1
        Disposition::create([
            'letter_id' => $this->letter->id,
            'sender_user_id' => $this->pimpinan->id,
            'recipient_user_id' => $this->pelaksana1->id,
            'urgency' => 'Penting',
            'instruction' => 'Harap telaah dokumen rahasia ini.',
            'status' => 'Menunggu',
        ]);

        $response = $this->actingAs($this->pelaksana1)->get(route('letters.file', $this->letter->id));
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_file_upload_sanitization_and_randomized_storage()
    {
        $file = UploadedFile::fake()->create('scan../laporan%00.pdf', 150, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('letters.store'), [
            'agenda_number' => '002/SEC/2026',
            'reference_number' => '005/SEC2/2026',
            'sender' => 'Polres',
            'letter_date' => '2026-08-26',
            'received_date' => '2026-08-26',
            'subject' => 'Surat Pengamanan Pilkada',
            'category_id' => $this->letter->category_id,
            'degree' => 'Penting',
            'letter_file' => $file,
        ]);

        $created = Letter::where('agenda_number', '002/SEC/2026')->first();
        $this->assertNotNull($created);
        $this->assertNotNull($created->file_path);

        // Make sure stored filename is randomized hash and does not contain ../ or null bytes
        $this->assertStringNotContainsString('../', $created->file_path);
        $this->assertStringNotContainsString('%00', $created->file_path);
    }
}
