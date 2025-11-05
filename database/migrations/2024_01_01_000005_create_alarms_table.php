<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alarms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('severity', ['critical', 'warning', 'info'])->default('info');
            $table->string('type');
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['active', 'acknowledged', 'resolved'])->default('active');
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'severity']);
            $table->index('device_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alarms');
    }
};