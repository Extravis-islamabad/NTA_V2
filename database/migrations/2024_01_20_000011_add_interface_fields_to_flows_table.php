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
            // Interface fields for NetFlow/IPFIX
            $table->integer('input_interface')->nullable()->after('dscp');
            $table->integer('output_interface')->nullable()->after('input_interface');

            // Add indexes
            $table->index('input_interface', 'idx_flows_input_if');
            $table->index('output_interface', 'idx_flows_output_if');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flows', function (Blueprint $table) {
            $table->dropIndex('idx_flows_input_if');
            $table->dropIndex('idx_flows_output_if');
            $table->dropColumn(['input_interface', 'output_interface']);
        });
    }
};
