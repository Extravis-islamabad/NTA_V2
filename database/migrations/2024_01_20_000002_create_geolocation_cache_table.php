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
        Schema::create('geolocation_cache', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->unique(); // Supports IPv4 and IPv6
            $table->char('country_code', 2)->nullable();
            $table->string('country_name', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('asn')->nullable();
            $table->string('as_name', 200)->nullable();
            $table->boolean('is_private')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps();

            // Index for cache expiration cleanup
            $table->index('expires_at', 'idx_geo_cache_expires');
            $table->index('country_code', 'idx_geo_cache_country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geolocation_cache');
    }
};
