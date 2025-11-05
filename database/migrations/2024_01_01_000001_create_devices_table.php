<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ip_address')->unique();
            $table->enum('type', ['router', 'switch', 'firewall', 'wireless_controller', 'checkpoint', 'palo_alto', 'fortigate', 'cisco_router'])->default('router');;
            $table->string('location')->nullable();
            $table->enum('status', ['online', 'offline', 'warning'])->default('online');
            $table->string('device_group')->nullable();
            $table->integer('interface_count')->default(0);
            $table->integer('flow_count')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('ip_address');
            $table->index('status');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};