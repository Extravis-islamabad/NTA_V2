<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->string('source_ip', 45);
            $table->string('destination_ip', 45);
            $table->integer('source_port');
            $table->integer('destination_port');
            $table->string('protocol', 10);
            $table->bigInteger('bytes')->default(0);
            $table->bigInteger('packets')->default(0);
            $table->timestamp('first_switched');
            $table->timestamp('last_switched');
            $table->string('application')->nullable();
            $table->integer('dscp')->nullable();
            $table->timestamps();
            
            $table->index(['device_id', 'created_at']);
            $table->index('source_ip');
            $table->index('destination_ip');
            $table->index('protocol');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flows');
    }
};