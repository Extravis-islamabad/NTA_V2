<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interfaces', function (Blueprint $table) {
            // SNMP interface index
            $table->integer('if_index')->nullable()->after('device_id');

            // Admin/Operational status
            $table->string('admin_status')->default('down')->after('status');
            $table->string('oper_status')->default('down')->after('admin_status');

            // SNMP polling timestamp
            $table->timestamp('last_polled')->nullable()->after('out_errors');

            // Previous counters for delta calculation
            $table->bigInteger('prev_in_octets')->nullable()->after('last_polled');
            $table->bigInteger('prev_out_octets')->nullable()->after('prev_in_octets');
            $table->timestamp('prev_poll_time')->nullable()->after('prev_out_octets');

            // Calculated bandwidth (bps)
            $table->bigInteger('in_bps')->default(0)->after('prev_poll_time');
            $table->bigInteger('out_bps')->default(0)->after('in_bps');

            // Utilization percentage
            $table->decimal('in_utilization', 5, 2)->default(0)->after('out_bps');
            $table->decimal('out_utilization', 5, 2)->default(0)->after('in_utilization');

            // Unique index for device + if_index
            $table->unique(['device_id', 'if_index'], 'device_interface_unique');
        });
    }

    public function down(): void
    {
        Schema::table('interfaces', function (Blueprint $table) {
            $table->dropUnique('device_interface_unique');
            $table->dropColumn([
                'if_index',
                'admin_status',
                'oper_status',
                'last_polled',
                'prev_in_octets',
                'prev_out_octets',
                'prev_poll_time',
                'in_bps',
                'out_bps',
                'in_utilization',
                'out_utilization'
            ]);
        });
    }
};
