<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->string('ssh_host')->nullable()->after('ip_address');
            $table->integer('ssh_port')->default(22)->after('ssh_host');
            $table->string('ssh_username')->nullable()->after('ssh_port');
            $table->text('ssh_password')->nullable()->after('ssh_username');
            $table->text('ssh_private_key')->nullable()->after('ssh_password');
            $table->boolean('ssh_enabled')->default(false)->after('ssh_private_key');
            $table->timestamp('last_ssh_connection')->nullable()->after('ssh_enabled');
            $table->text('ssh_connection_status')->nullable()->after('last_ssh_connection');
        });
    }

    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn([
                'ssh_host',
                'ssh_port',
                'ssh_username',
                'ssh_password',
                'ssh_private_key',
                'ssh_enabled',
                'last_ssh_connection',
                'ssh_connection_status'
            ]);
        });
    }
};
