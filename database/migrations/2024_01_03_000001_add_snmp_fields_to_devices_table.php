<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            // SNMP Configuration
            $table->boolean('snmp_enabled')->default(false)->after('ssh_connection_status');
            $table->enum('snmp_version', ['v1', 'v2c', 'v3'])->default('v2c')->after('snmp_enabled');
            $table->integer('snmp_port')->default(161)->after('snmp_version');

            // SNMP v1/v2c Community String
            $table->text('snmp_community')->nullable()->after('snmp_port');

            // SNMP v3 Security
            $table->string('snmp_username')->nullable()->after('snmp_community');
            $table->enum('snmp_security_level', ['noAuthNoPriv', 'authNoPriv', 'authPriv'])->default('authPriv')->after('snmp_username');
            $table->enum('snmp_auth_protocol', ['MD5', 'SHA', 'SHA256', 'SHA512'])->default('SHA')->after('snmp_security_level');
            $table->text('snmp_auth_password')->nullable()->after('snmp_auth_protocol');
            $table->enum('snmp_priv_protocol', ['DES', 'AES', 'AES192', 'AES256'])->default('AES')->after('snmp_auth_password');
            $table->text('snmp_priv_password')->nullable()->after('snmp_priv_protocol');

            // SNMP Polling Settings
            $table->integer('snmp_poll_interval')->default(300)->after('snmp_priv_password'); // seconds
            $table->timestamp('last_snmp_poll')->nullable()->after('snmp_poll_interval');
            $table->text('snmp_connection_status')->nullable()->after('last_snmp_poll');

            // Device Info from SNMP
            $table->string('snmp_sys_name')->nullable()->after('snmp_connection_status');
            $table->string('snmp_sys_descr')->nullable()->after('snmp_sys_name');
            $table->string('snmp_sys_location')->nullable()->after('snmp_sys_descr');
            $table->string('snmp_sys_contact')->nullable()->after('snmp_sys_location');
            $table->bigInteger('snmp_sys_uptime')->nullable()->after('snmp_sys_contact'); // in seconds
        });
    }

    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn([
                'snmp_enabled',
                'snmp_version',
                'snmp_port',
                'snmp_community',
                'snmp_username',
                'snmp_security_level',
                'snmp_auth_protocol',
                'snmp_auth_password',
                'snmp_priv_protocol',
                'snmp_priv_password',
                'snmp_poll_interval',
                'last_snmp_poll',
                'snmp_connection_status',
                'snmp_sys_name',
                'snmp_sys_descr',
                'snmp_sys_location',
                'snmp_sys_contact',
                'snmp_sys_uptime'
            ]);
        });
    }
};
