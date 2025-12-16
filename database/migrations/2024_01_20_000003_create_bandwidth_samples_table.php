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
        Schema::create('bandwidth_samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->foreignId('interface_id')->nullable()->constrained('interfaces')->onDelete('cascade');

            // Traffic metrics
            $table->bigInteger('in_bytes')->default(0);
            $table->bigInteger('out_bytes')->default(0);
            $table->bigInteger('in_packets')->default(0);
            $table->bigInteger('out_packets')->default(0);

            // Calculated bandwidth (bits per second)
            $table->bigInteger('in_bps')->default(0);
            $table->bigInteger('out_bps')->default(0);

            // Flow count in this sample period
            $table->integer('flow_count')->default(0);

            // Sample timestamp
            $table->timestamp('sampled_at');
            $table->timestamps();

            // Indexes for time-series queries
            $table->index(['device_id', 'sampled_at'], 'idx_bandwidth_device_time');
            $table->index(['interface_id', 'sampled_at'], 'idx_bandwidth_interface_time');
            $table->index('sampled_at', 'idx_bandwidth_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bandwidth_samples');
    }
};
