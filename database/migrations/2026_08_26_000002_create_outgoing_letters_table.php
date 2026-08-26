<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outgoing_letters', function (Blueprint $table) {
            $table->id();
            $table->string('agenda_number')->unique();
            $table->string('reference_number');
            $table->date('letter_date');
            $table->string('destination');
            $table->string('subject');
            $table->text('summary')->nullable();
            $table->foreignId('category_id')->constrained('letter_categories')->onDelete('restrict');
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type')->default('pdf');
            $table->integer('file_size')->default(0);
            $table->enum('status', ['Konsep', 'Disetujui', 'Terkirim', 'Arsip'])->default('Terkirim');
            $table->enum('degree', ['Biasa', 'Penting', 'Rahasia', 'Sangat Segera'])->default('Biasa');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outgoing_letters');
    }
};
