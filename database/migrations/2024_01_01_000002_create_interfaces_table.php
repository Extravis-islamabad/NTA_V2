<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interfaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['ethernet', 'fiber', 'wireless', 'virtual'])->default('ethernet');
            $table->enum('status', ['up', 'down', 'admin_down'])->default('down');
            $table->bigInteger('speed')->default(0); // Add this line
            $table->bigInteger('in_octets')->default(0);
            $table->bigInteger('out_octets')->default(0);
            $table->bigInteger('in_errors')->default(0);
            $table->bigInteger('out_errors')->default(0);
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index('device_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interfaces');
    }
};