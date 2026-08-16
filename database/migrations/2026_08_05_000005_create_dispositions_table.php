<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispositions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('letter_id')->constrained('letters')->onDelete('cascade');
            $table->foreignId('sender_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recipient_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recipient_department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->text('instruction');
            $table->enum('urgency', ['Biasa', 'Penting', 'Rahasia', 'Sangat Segera'])->default('Biasa');
            $table->date('due_date')->nullable();
            $table->enum('status', ['Menunggu', 'Diproses', 'Selesai', 'Ditolak'])->default('Menunggu');
            $table->text('follow_up_notes')->nullable();
            $table->timestamp('followed_up_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('disposition_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disposition_id')->constrained('dispositions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // e.g. Disposisi Dikirim, Dibaca, Diproses, Selesai
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disposition_histories');
        Schema::dropIfExists('dispositions');
    }
};
