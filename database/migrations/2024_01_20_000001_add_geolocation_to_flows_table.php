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
        Schema::table('flows', function (Blueprint $table) {
            // Source geolocation fields
            $table->char('src_country_code', 2)->nullable()->after('application');
            $table->string('src_country_name', 100)->nullable()->after('src_country_code');
            $table->string('src_city', 100)->nullable()->after('src_country_name');
            $table->decimal('src_latitude', 10, 7)->nullable()->after('src_city');
            $table->decimal('src_longitude', 10, 7)->nullable()->after('src_latitude');
            $table->integer('src_asn')->nullable()->after('src_longitude');

            // Destination geolocation fields
            $table->char('dst_country_code', 2)->nullable()->after('src_asn');
            $table->string('dst_country_name', 100)->nullable()->after('dst_country_code');
            $table->string('dst_city', 100)->nullable()->after('dst_country_name');
            $table->decimal('dst_latitude', 10, 7)->nullable()->after('dst_city');
            $table->decimal('dst_longitude', 10, 7)->nullable()->after('dst_latitude');
            $table->integer('dst_asn')->nullable()->after('dst_longitude');

            // Application category for grouping
            $table->string('app_category', 50)->nullable()->after('dst_asn');

            // Indexes for efficient querying
            $table->index('src_country_code', 'idx_flows_src_country');
            $table->index('dst_country_code', 'idx_flows_dst_country');
            $table->index('app_category', 'idx_flows_app_category');
            $table->index(['device_id', 'app_category', 'created_at'], 'idx_flows_device_app_time');
            $table->index(['dst_country_code', 'created_at'], 'idx_flows_dst_country_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flows', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex('idx_flows_src_country');
            $table->dropIndex('idx_flows_dst_country');
            $table->dropIndex('idx_flows_app_category');
            $table->dropIndex('idx_flows_device_app_time');
            $table->dropIndex('idx_flows_dst_country_time');

            // Drop columns
            $table->dropColumn([
                'src_country_code',
                'src_country_name',
                'src_city',
                'src_latitude',
                'src_longitude',
                'src_asn',
                'dst_country_code',
                'dst_country_name',
                'dst_city',
                'dst_latitude',
                'dst_longitude',
                'dst_asn',
                'app_category',
            ]);
        });
    }
};
