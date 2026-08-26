<?php

namespace Tests\Feature;

use App\Models\LetterCategory;
use App\Models\OutgoingLetter;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OutgoingLetterTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $pimpinan;
    private User $pelaksana;
    private LetterCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        $pimpinanRole = Role::firstOrCreate(['name' => 'pimpinan'], ['display_name' => 'Pimpinan']);
        $pelaksanaRole = Role::firstOrCreate(['name' => 'pelaksana'], ['display_name' => 'Pelaksana']);

        $this->category = LetterCategory::create([
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

        $this->pelaksana = User::create([
            'name' => 'Staf Pelaksana',
            'email' => 'staf@test.com',
            'password' => bcrypt('password'),
            'role_id' => $pelaksanaRole->id,
        ]);
    }

    public function test_admin_can_store_outgoing_letter()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('surat_keluar.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('outgoing-letters.store'), [
            'agenda_number' => '001/SK/2026',
            'reference_number' => '005/01/DKH/2026',
            'letter_date' => '2026-08-26',
            'destination' => 'Kantor Camat Sukoharjo',
            'subject' => 'Laporan Bulanan Kelurahan',
            'summary' => 'Ringkasan laporan bulanan administrasi.',
            'category_id' => $this->category->id,
            'degree' => 'Biasa',
            'status' => 'Terkirim',
            'letter_file' => $file,
        ]);

        $this->assertDatabaseHas('outgoing_letters', [
            'agenda_number' => '001/SK/2026',
            'reference_number' => '005/01/DKH/2026',
            'destination' => 'Kantor Camat Sukoharjo',
        ]);

        $letter = OutgoingLetter::where('agenda_number', '001/SK/2026')->first();
        $response->assertRedirect(route('outgoing-letters.show', $letter->id));
    }

    public function test_pimpinan_and_admin_can_view_outgoing_letters_index()
    {
        $responseAdmin = $this->actingAs($this->admin)->get(route('outgoing-letters.index'));
        $responseAdmin->assertStatus(200);

        $responsePimpinan = $this->actingAs($this->pimpinan)->get(route('outgoing-letters.index'));
        $responsePimpinan->assertStatus(200);
    }

    public function test_pelaksana_accessing_outgoing_letters_is_redirected()
    {
        $response = $this->actingAs($this->pelaksana)->get(route('outgoing-letters.index'));
        $response->assertRedirect(route('dispositions.index'));
    }

    public function test_admin_can_store_outgoing_letter_without_file()
    {
        $response = $this->actingAs($this->admin)->post(route('outgoing-letters.store'), [
            'agenda_number' => '002/SK/2026',
            'reference_number' => '005/02/DKH/2026',
            'letter_date' => '2026-08-26',
            'destination' => 'Dinas Kependudukan & Pencatatan Sipil',
            'subject' => 'Surat Pengantar Data Penduduk',
            'category_id' => $this->category->id,
            'degree' => 'Biasa',
            'status' => 'Terkirim',
        ]);

        $this->assertDatabaseHas('outgoing_letters', [
            'agenda_number' => '002/SK/2026',
            'file_path' => null,
        ]);

        $letter = OutgoingLetter::where('agenda_number', '002/SK/2026')->first();
        $response->assertRedirect(route('outgoing-letters.show', $letter->id));
    }
}
