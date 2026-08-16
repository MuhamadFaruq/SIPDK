<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->string('agenda_number')->unique();
            $table->string('reference_number');
            $table->date('letter_date');
            $table->date('received_date');
            $table->string('sender');
            $table->string('subject');
            $table->text('summary')->nullable();
            $table->foreignId('category_id')->constrained('letter_categories')->onDelete('restrict');
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->string('file_type')->default('pdf');
            $table->integer('file_size')->default(0);
            $table->enum('status', ['Baru', 'Dibaca', 'Didisposisi', 'Diproses', 'Selesai', 'Arsip', 'Pending', 'Ditolak'])->default('Baru');
            $table->enum('degree', ['Biasa', 'Penting', 'Rahasia', 'Sangat Segera'])->default('Biasa');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
