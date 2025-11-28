<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('client_name');
            $table->string('model_name');
            $table->string('device_serial_id');
            $table->enum('device_type', ['PC', 'Laptop', 'Mobile Phone']);
            $table->text('problem_description');
            $table->text('fix_description')->nullable();
            $table->string('phone_number');
            $table->string('client_email');
            $table->enum('status', ['on-going', 'complete'])->default('on-going');
            $table->text('additional_notes')->nullable();
            $table->string('pdf_hash')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

