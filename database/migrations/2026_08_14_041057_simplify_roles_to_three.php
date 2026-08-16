<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new roles if they don't exist
        DB::table('roles')->insertOrIgnore([
            ['name' => 'admin', 'display_name' => 'Admin', 'description' => 'Admin Sistem & Tata Usaha', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'pimpinan', 'display_name' => 'Pimpinan', 'description' => 'Lurah & Sekretaris (Monitoring & Disposisi)', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'pelaksana', 'display_name' => 'Pelaksana', 'description' => 'Petugas Pelaksana (Menerima Disposisi)', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $adminId = DB::table('roles')->where('name', 'admin')->value('id');
        $pimpinanId = DB::table('roles')->where('name', 'pimpinan')->value('id');
        $pelaksanaId = DB::table('roles')->where('name', 'pelaksana')->value('id');

        // Migrate existing users to new roles
        // tu -> admin
        $tuId = DB::table('roles')->where('name', 'tu')->value('id');
        if ($tuId) DB::table('users')->where('role_id', $tuId)->update(['role_id' => $adminId]);

        // lurah, sekretaris -> pimpinan
        $lurahId = DB::table('roles')->where('name', 'lurah')->value('id');
        $sekretarisId = DB::table('roles')->where('name', 'sekretaris')->value('id');
        if ($lurahId) DB::table('users')->where('role_id', $lurahId)->update(['role_id' => $pimpinanId]);
        if ($sekretarisId) DB::table('users')->where('role_id', $sekretarisId)->update(['role_id' => $pimpinanId]);

        // kasi, staff -> pelaksana
        $kasiId = DB::table('roles')->where('name', 'kasi')->value('id');
        $staffId = DB::table('roles')->where('name', 'staff')->value('id');
        if ($kasiId) DB::table('users')->where('role_id', $kasiId)->update(['role_id' => $pelaksanaId]);
        if ($staffId) DB::table('users')->where('role_id', $staffId)->update(['role_id' => $pelaksanaId]);

        // Delete old roles
        DB::table('roles')->whereIn('name', ['tu', 'lurah', 'sekretaris', 'kasi', 'staff'])->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('roles')->insertOrIgnore([
            ['name' => 'tu', 'display_name' => 'Petugas TU', 'description' => 'Mencatat surat masuk, upload berkas, & mengelola agenda.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'lurah', 'display_name' => 'Lurah', 'description' => 'Melihat seluruh surat, memberikan disposisi utama, & monitoring.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'sekretaris', 'display_name' => 'Sekretaris Kelurahan', 'description' => 'Membantu disposisi, verifikasi surat, & monitoring status.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'kasi', 'display_name' => 'Kasi / Kaur', 'description' => 'Menerima disposisi, menindaklanjuti, & menyelesaikan tugas.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'staff', 'display_name' => 'Staff Operasional', 'description' => 'Pelaksana tugas disposisi dari Kasi/Kaur.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
};
