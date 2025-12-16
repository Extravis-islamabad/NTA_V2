<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->timestamps();
        });

        // Insert default settings (IPs and ports should be configured by admin)
        $defaults = [
            ['key' => 'collector_ip', 'value' => '', 'type' => 'string'],
            ['key' => 'netflow_port', 'value' => '', 'type' => 'integer'],
            ['key' => 'sflow_port', 'value' => '', 'type' => 'integer'],
            ['key' => 'ipfix_port', 'value' => '', 'type' => 'integer'],
            ['key' => 'retention_days', 'value' => '7', 'type' => 'integer'],
            ['key' => 'netflow_v5', 'value' => 'true', 'type' => 'boolean'],
            ['key' => 'netflow_v9', 'value' => 'true', 'type' => 'boolean'],
            ['key' => 'ipfix', 'value' => 'true', 'type' => 'boolean'],
            ['key' => 'sflow', 'value' => 'false', 'type' => 'boolean'],
            ['key' => 'sample_rate', 'value' => '1', 'type' => 'integer'],
            ['key' => 'active_timeout', 'value' => '60', 'type' => 'integer'],
            ['key' => 'aggregation_enabled', 'value' => 'true', 'type' => 'boolean'],
            ['key' => 'aggregation_interval', 'value' => '1min', 'type' => 'string'],
            ['key' => 'dns_resolution', 'value' => 'false', 'type' => 'boolean'],
            ['key' => 'geolocation_enabled', 'value' => 'true', 'type' => 'boolean'],
            ['key' => 'as_lookup_enabled', 'value' => 'true', 'type' => 'boolean'],
            ['key' => 'traffic_threshold', 'value' => '1000', 'type' => 'integer'],
            ['key' => 'offline_timeout', 'value' => '5', 'type' => 'integer'],
            ['key' => 'utilization_warning', 'value' => '80', 'type' => 'integer'],
            ['key' => 'utilization_critical', 'value' => '95', 'type' => 'integer'],
        ];

        foreach ($defaults as $setting) {
            DB::table('settings')->insert([
                'key' => $setting['key'],
                'value' => $setting['value'],
                'type' => $setting['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
