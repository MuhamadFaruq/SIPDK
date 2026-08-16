<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Disposition;
use App\Models\DispositionHistory;
use App\Models\Letter;
use App\Models\LetterCategory;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $roles = [
            ['name' => 'admin', 'display_name' => 'Administrator', 'description' => 'Mengelola sistem & mencatat persuratan.'],
            ['name' => 'pimpinan', 'display_name' => 'Pimpinan', 'description' => 'Melihat seluruh surat & memberikan disposisi.'],
            ['name' => 'pelaksana', 'display_name' => 'Pelaksana', 'description' => 'Menerima disposisi & menindaklanjuti.'],
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r['name']], $r);
        }

        // 2. Departments
        $depts = [
            ['code' => 'SEKRETARIAT', 'name' => 'Sekretariat Kelurahan', 'head_title' => 'Sekretaris Kelurahan'],
            ['code' => 'PEMERINTAHAN', 'name' => 'Seksi Pemerintahan', 'head_title' => 'Kasi Pemerintahan'],
            ['code' => 'PMD', 'name' => 'Seksi Pemberdayaan Masyarakat & Desa', 'head_title' => 'Kasi PMD'],
            ['code' => 'TRANTIB', 'name' => 'Seksi Ketenteraman & Ketertiban', 'head_title' => 'Kasi Trantib'],
            ['code' => 'KESRA', 'name' => 'Seksi Kesejahteraan Rakyat', 'head_title' => 'Kasi Kesra'],
            ['code' => 'UMUM_KEU', 'name' => 'Subbagian Umum & Keuangan', 'head_title' => 'Kaur Umum & Keuangan'],
        ];

        foreach ($depts as $d) {
            Department::firstOrCreate(['code' => $d['code']], $d);
        }

        // 3. Categories
        $categories = [
            ['code' => 'UND', 'name' => 'Surat Undangan', 'description' => 'Undangan rapat, dinas, atau kegiatan kemasyarakatan.'],
            ['code' => 'EDR', 'name' => 'Surat Edaran', 'description' => 'Instruksi dan edaran dari Pemkot/Kecamatan.'],
            ['code' => 'PMH', 'name' => 'Surat Permohonan', 'description' => 'Permohonan bantuan, fasilitasi, atau izin.'],
            ['code' => 'PBT', 'name' => 'Surat Pemberitahuan', 'description' => 'Pemberitahuan resmi instansi luar.'],
            ['code' => 'DNS', 'name' => 'Surat Dinas Umum', 'description' => 'Surat masuk kedinasan umum.'],
        ];

        foreach ($categories as $c) {
            LetterCategory::firstOrCreate(['code' => $c['code']], $c);
        }

        // Roles & Dept maps
        $adminRole = Role::where('name', 'admin')->first();
        $pimpinanRole = Role::where('name', 'pimpinan')->first();
        $pelaksanaRole = Role::where('name', 'pelaksana')->first();

        $pemDept = Department::where('code', 'PEMERINTAHAN')->first();
        $pmdDept = Department::where('code', 'PMD')->first();
        $sekretariatDept = Department::where('code', 'SEKRETARIAT')->first();

        // 4. Users
        $password = Hash::make('password');

        $admin = User::firstOrCreate(['email' => 'admin@kelurahan.go.id'], [
            'name' => 'Administrator SIPDK',
            'password' => $password,
            'role_id' => $adminRole->id,
            'nip' => '19850101 201001 1 001',
            'jabatan' => 'System Administrator',
            'phone' => '081234567890',
        ]);

        $tuUser = User::firstOrCreate(['email' => 'tu@kelurahan.go.id'], [
            'name' => 'Siti Aminah, A.Md',
            'password' => $password,
            'role_id' => $adminRole->id,
            'department_id' => $sekretariatDept->id,
            'nip' => '19900315 201402 2 003',
            'jabatan' => 'Petugas Tata Usaha & Agendaris',
            'phone' => '081298765432',
        ]);

        $lurahUser = User::firstOrCreate(['email' => 'lurah@kelurahan.go.id'], [
            'name' => 'H. Bambang Sutarjo, S.STP, M.Si',
            'password' => $password,
            'role_id' => $pimpinanRole->id,
            'nip' => '19780512 199803 1 002',
            'jabatan' => 'Lurah Dukuh',
            'phone' => '081122334455',
        ]);

        $sekretarisUser = User::firstOrCreate(['email' => 'sekretaris@kelurahan.go.id'], [
            'name' => 'Drs. Rahmat Hidayat',
            'password' => $password,
            'role_id' => $pimpinanRole->id,
            'department_id' => $sekretariatDept->id,
            'nip' => '19800720 200501 1 005',
            'jabatan' => 'Sekretaris Kelurahan',
            'phone' => '081344556677',
        ]);

        $kasiPemUser = User::firstOrCreate(['email' => 'kasi_pem@kelurahan.go.id'], [
            'name' => 'Ahmad Fauzi, S.IP',
            'password' => $password,
            'role_id' => $pelaksanaRole->id,
            'department_id' => $pemDept->id,
            'nip' => '19841110 200903 1 008',
            'jabatan' => 'Kasi Pemerintahan',
            'phone' => '081566778899',
        ]);

        $kasiPmdUser = User::firstOrCreate(['email' => 'kasi_pmd@kelurahan.go.id'], [
            'name' => 'Dewi Sartika, S.E.',
            'password' => $password,
            'role_id' => $pelaksanaRole->id,
            'department_id' => $pmdDept->id,
            'nip' => '19870425 201101 2 006',
            'jabatan' => 'Kasi Pemberdayaan Masyarakat',
            'phone' => '081677889900',
        ]);

        $staffUser = User::firstOrCreate(['email' => 'staff@kelurahan.go.id'], [
            'name' => 'Budi Santoso',
            'password' => $password,
            'role_id' => $pelaksanaRole->id,
            'department_id' => $pemDept->id,
            'nip' => '19950812 202001 1 012',
            'jabatan' => 'Staff Pelaksana Pemerintahan',
            'phone' => '081788990011',
        ]);

        // Sample PDF File Creation in storage/app/public/letters/
        Storage::disk('public')->makeDirectory('letters');
        $dummyPath = 'letters/sample_surat_01.pdf';
        if (!Storage::disk('public')->exists($dummyPath)) {
            // Write a simple placeholder file
            Storage::disk('public')->put($dummyPath, "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R >>\nendobj\n4 0 obj\n<< /Length 55 >>\nstream\nBT /F1 12 Tf 50 700 TD (SURAT RESMI SIPDK KELURAHAN) Tj ET\nendstream\nendobj\nxref\n0 5\n0000000000 65535 f\n0000000009 00000 n\n0000000058 00000 n\n0000000115 00000 n\n0000000213 00000 n\ntrailer\n<< /Size 5 /Root 1 0 R >>\nstartxref\n318\n%%EOF");
        }

        // 5. Sample Letters
        $catUndangan = LetterCategory::where('code', 'UND')->first();
        $catEdaran = LetterCategory::where('code', 'EDR')->first();
        $catPermohonan = LetterCategory::where('code', 'PMH')->first();

        $letter1 = Letter::firstOrCreate(['agenda_number' => 'AGD-2026/08/001'], [
            'reference_number' => '005/124/KEC.SKH/2026',
            'letter_date' => now()->subDays(2)->format('Y-m-d'),
            'received_date' => now()->subDays(2)->format('Y-m-d'),
            'sender' => 'Kecamatan Sukoharjo - Bagian Tata Pemerintahan',
            'subject' => 'Undangan Rapat Koordinasi Penataan Batas Wilayah RT/RW Tahun 2026',
            'summary' => 'Mengharap kehadiran Lurah dan Kasi Pemerintahan dalam rapat koordinasi penataan wilayah administrasi RT/RW.',
            'category_id' => $catUndangan->id,
            'file_path' => $dummyPath,
            'file_name' => 'undangan_rapat_koordinasi.pdf',
            'file_type' => 'pdf',
            'file_size' => 102450,
            'status' => 'Didisposisi',
            'degree' => 'Penting',
            'created_by' => $tuUser->id,
        ]);

        $letter2 = Letter::firstOrCreate(['agenda_number' => 'AGD-2026/08/002'], [
            'reference_number' => '440/088/DISKES/2026',
            'letter_date' => now()->subDay()->format('Y-m-d'),
            'received_date' => now()->subDay()->format('Y-m-d'),
            'sender' => 'Dinas Kesehatan Kabupaten Sukoharjo',
            'subject' => 'Pelaksanaan Program Posyandu Remaja dan Penanganan Stunting',
            'summary' => 'Sosialisasi dan jadwal supervisi pelayanan Posyandu Remaja serta verifikasi data balita terindikasi stunting.',
            'category_id' => $catEdaran->id,
            'file_path' => $dummyPath,
            'file_name' => 'edaran_posyandu_stunting.pdf',
            'file_type' => 'pdf',
            'file_size' => 204800,
            'status' => 'Diproses',
            'degree' => 'Sangat Segera',
            'created_by' => $tuUser->id,
        ]);

        $letter3 = Letter::firstOrCreate(['agenda_number' => 'AGD-2026/08/003'], [
            'reference_number' => '012/RW.04/DKH/VIII/2026',
            'letter_date' => now()->format('Y-m-d'),
            'received_date' => now()->format('Y-m-d'),
            'sender' => 'Pengurus RW 04 Kelurahan Dukuh',
            'subject' => 'Permohonan Bantuan Perbaikan Saluran Air / Drainase Lingkungan',
            'summary' => 'Pengajuan usulan kerja bakti dan bantuan material perbaikan selokan tersumbat menjelang musim hujan.',
            'category_id' => $catPermohonan->id,
            'file_path' => $dummyPath,
            'file_name' => 'permohonan_drainase_rw04.pdf',
            'file_type' => 'pdf',
            'file_size' => 158000,
            'status' => 'Baru',
            'degree' => 'Biasa',
            'created_by' => $tuUser->id,
        ]);

        // 6. Sample Dispositions
        $disp1 = Disposition::firstOrCreate(['letter_id' => $letter1->id, 'recipient_user_id' => $kasiPemUser->id], [
            'sender_user_id' => $lurahUser->id,
            'recipient_department_id' => $pemDept->id,
            'instruction' => 'Harap hadir mendampingi dan siapkan peta batas wilayah RT 01-08 terbaru.',
            'urgency' => 'Penting',
            'due_date' => now()->addDays(1)->format('Y-m-d'),
            'status' => 'Diproses',
        ]);

        DispositionHistory::firstOrCreate(['disposition_id' => $disp1->id, 'action' => 'Disposisi Dikirim'], [
            'user_id' => $lurahUser->id,
            'notes' => 'Disposisi utama disahkan oleh Lurah',
        ]);

        $disp2 = Disposition::firstOrCreate(['letter_id' => $letter2->id, 'recipient_user_id' => $kasiPmdUser->id], [
            'sender_user_id' => $lurahUser->id,
            'recipient_department_id' => $pmdDept->id,
            'instruction' => 'Koordinasikan dengan Kader Posyandu RW 01-06 dan buatkan rekap jadwal pelaksanaannya.',
            'urgency' => 'Sangat Segera',
            'due_date' => now()->addDays(2)->format('Y-m-d'),
            'status' => 'Diproses',
        ]);

        // 7. Audit Log Seed
        AuditLog::log($tuUser, 'Mencatat Surat Masuk', 'Surat Masuk', 'Surat No. 005/124/KEC.SKM/2026 berhasil diagendakan.');
        AuditLog::log($lurahUser, 'Memberikan Disposisi', 'Disposisi', 'Disposisi Surat AGD-2026/08/001 ke Kasi Pemerintahan.');

        // 8. Notification Seed
        Notification::create([
            'user_id' => $kasiPemUser->id,
            'title' => 'Disposisi Baru dari Lurah',
            'message' => 'Anda menerima disposisi untuk surat: Undangan Rapat Koordinasi Penataan Batas Wilayah.',
            'link' => '/dispositions',
            'is_read' => false,
        ]);
    }
}
