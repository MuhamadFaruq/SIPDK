<?php

namespace Tests\Feature;

use App\Models\Disposition;
use App\Models\Letter;
use App\Models\LetterCategory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LetterAccessControlTest extends TestCase
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

        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        $pimpinanRole = Role::firstOrCreate(['name' => 'pimpinan'], ['display_name' => 'Pimpinan']);
        $pelaksanaRole = Role::firstOrCreate(['name' => 'pelaksana'], ['display_name' => 'Pelaksana']);

        $category = LetterCategory::create([
            'code' => 'DNS',
            'name' => 'Dinas',
            'description' => 'Surat Dinas Umum'
        ]);

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
        ]);

        $this->pimpinan = User::create([
            'name' => 'Lurah User',
            'email' => 'lurah@test.com',
            'password' => bcrypt('password'),
            'role_id' => $pimpinanRole->id,
        ]);

        $this->pelaksana1 = User::create([
            'name' => 'Kasi Pemerintahan',
            'email' => 'kasi1@test.com',
            'password' => bcrypt('password'),
            'role_id' => $pelaksanaRole->id,
        ]);

        $this->pelaksana2 = User::create([
            'name' => 'Kasi PMD',
            'email' => 'kasi2@test.com',
            'password' => bcrypt('password'),
            'role_id' => $pelaksanaRole->id,
        ]);

        $this->letter = Letter::create([
            'agenda_number' => '001/SM/2026',
            'reference_number' => '005/123/2026',
            'sender' => 'Kantor Camat',
            'letter_date' => now(),
            'received_date' => now(),
            'subject' => 'Undangan Rapat Koordinasi',
            'category_id' => $category->id,
            'status' => 'Baru',
            'file_path' => 'letters/test.pdf',
            'created_by' => $this->admin->id,
        ]);
    }

    public function test_admin_and_pimpinan_can_view_letter_index()
    {
        $responseAdmin = $this->actingAs($this->admin)->get(route('letters.index'));
        $responseAdmin->assertStatus(200);

        $responsePimpinan = $this->actingAs($this->pimpinan)->get(route('letters.index'));
        $responsePimpinan->assertStatus(200);
    }

    public function test_pelaksana_accessing_letter_index_is_redirected_to_dispositions()
    {
        $response = $this->actingAs($this->pelaksana1)->get(route('letters.index'));
        $response->assertRedirect(route('dispositions.index'));
    }

    public function test_pelaksana_cannot_view_undisposed_letter_detail()
    {
        $response = $this->actingAs($this->pelaksana1)->get(route('letters.show', $this->letter->id));
        $response->assertStatus(403);
    }

    public function test_pelaksana_can_view_letter_after_it_is_disposed_to_them()
    {
        // Pimpinan disposes letter to pelaksana1
        Disposition::create([
            'letter_id' => $this->letter->id,
            'sender_user_id' => $this->pimpinan->id,
            'recipient_user_id' => $this->pelaksana1->id,
            'urgency' => 'Penting',
            'instruction' => 'Harap ditindaklanjuti segera.',
            'status' => 'Menunggu',
        ]);

        // pelaksana1 can view
        $response1 = $this->actingAs($this->pelaksana1)->get(route('letters.show', $this->letter->id));
        $response1->assertStatus(200);

        // pelaksana2 (who is not recipient) still gets 403
        $response2 = $this->actingAs($this->pelaksana2)->get(route('letters.show', $this->letter->id));
        $response2->assertStatus(403);
    }

    public function test_admin_can_create_letter_without_file()
    {
        $response = $this->actingAs($this->admin)->post(route('letters.store'), [
            'agenda_number' => '002/SM/2026',
            'reference_number' => '005/999/2026',
            'sender' => 'Dinas Sosial',
            'letter_date' => '2026-08-26',
            'received_date' => '2026-08-26',
            'subject' => 'Surat Pemberitahuan Bantuan',
            'category_id' => $this->letter->category_id,
            'degree' => 'Biasa',
        ]);

        $this->assertDatabaseHas('letters', [
            'agenda_number' => '002/SM/2026',
            'file_path' => null,
        ]);

        $newLetter = Letter::where('agenda_number', '002/SM/2026')->first();
        $response->assertRedirect(route('letters.show', $newLetter->id));
    }
}
