<?php

namespace Tests\Feature;

use App\Models\Disposition;
use App\Models\Letter;
use App\Models\LetterCategory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CascadingDispositionTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $lurah;
    private User $kasi;
    private User $staf;
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
            'description' => 'Surat Dinas'
        ]);

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
        ]);

        $this->lurah = User::create([
            'name' => 'Lurah Dukuh',
            'email' => 'lurah@test.com',
            'password' => bcrypt('password'),
            'role_id' => $pimpinanRole->id,
        ]);

        $this->kasi = User::create([
            'name' => 'Kasi Pemerintahan',
            'email' => 'kasi@test.com',
            'password' => bcrypt('password'),
            'role_id' => $pelaksanaRole->id,
        ]);

        $this->staf = User::create([
            'name' => 'Staf Teknis',
            'email' => 'staf@test.com',
            'password' => bcrypt('password'),
            'role_id' => $pelaksanaRole->id,
        ]);

        $this->letter = Letter::create([
            'agenda_number' => '001/SM/2026',
            'reference_number' => '005/123/2026',
            'sender' => 'Kantor Camat',
            'letter_date' => now(),
            'received_date' => now(),
            'subject' => 'Surat Masuk Urusan Wilayah',
            'category_id' => $category->id,
            'status' => 'Baru',
            'file_path' => 'letters/test.pdf',
            'created_by' => $this->admin->id,
        ]);
    }

    public function test_cascading_disposition_from_lurah_to_kasi_to_staf()
    {
        // 1. Lurah sends initial disposition to Kasi
        $dispositionLurah = Disposition::create([
            'letter_id' => $this->letter->id,
            'sender_user_id' => $this->lurah->id,
            'recipient_user_id' => $this->kasi->id,
            'urgency' => 'Penting',
            'instruction' => 'Koordinasikan dan tindak lanjuti.',
            'status' => 'Menunggu',
        ]);

        // Kasi can access letter
        $resKasi = $this->actingAs($this->kasi)->get(route('letters.show', $this->letter->id));
        $resKasi->assertStatus(200);

        // 2. Kasi forwards (cascades) disposition to Staf with specific instruction
        $forwardResponse = $this->actingAs($this->kasi)->post(route('dispositions.forward', $dispositionLurah->id), [
            'recipients' => [$this->staf->id],
            'instruction' => 'Tolong survei lokasi batas wilayah RT 02 besok pagi.',
            'urgency' => 'Penting',
        ]);

        $forwardResponse->assertRedirect();
        $forwardResponse->assertSessionHas('success');

        // Check child disposition exists
        $this->assertDatabaseHas('dispositions', [
            'parent_id' => $dispositionLurah->id,
            'letter_id' => $this->letter->id,
            'sender_user_id' => $this->kasi->id,
            'recipient_user_id' => $this->staf->id,
            'instruction' => 'Tolong survei lokasi batas wilayah RT 02 besok pagi.',
        ]);

        // Staf can now access the letter
        $resStaf = $this->actingAs($this->staf)->get(route('letters.show', $this->letter->id));
        $resStaf->assertStatus(200);

        // 3. Staf completes the task
        $childDisp = Disposition::where('recipient_user_id', $this->staf->id)->first();
        $this->actingAs($this->staf)->put(route('dispositions.follow-up', $childDisp->id), [
            'status' => 'Selesai',
            'follow_up_notes' => 'Survei lokasi RT 02 selesai dilakukan.',
        ]);

        $this->assertDatabaseHas('dispositions', [
            'id' => $childDisp->id,
            'status' => 'Selesai',
            'follow_up_notes' => 'Survei lokasi RT 02 selesai dilakukan.',
        ]);
    }
}
