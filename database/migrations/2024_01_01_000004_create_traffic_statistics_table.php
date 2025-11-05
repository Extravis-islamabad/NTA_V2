<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('traffic_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->foreignId('interface_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('protocol')->nullable();
            $table->string('application')->nullable();
            $table->bigInteger('total_bytes')->default(0);
            $table->bigInteger('total_packets')->default(0);
            $table->integer('flow_count')->default(0);
            $table->decimal('avg_speed_bps', 15, 2)->default(0);
            $table->decimal('max_speed_bps', 15, 2)->default(0);
            $table->enum('interval_type', ['1min', '10min', '1hour', '1day']);
            $table->timestamp('interval_start');
            $table->timestamp('interval_end');
            $table->timestamps();
            
            $table->index(['device_id', 'interval_type', 'interval_start']);
            $table->index('protocol');
            $table->index('application');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traffic_statistics');
    }
};