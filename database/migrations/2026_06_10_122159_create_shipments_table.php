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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('job_number')->unique();
            $table->string('shipment_number')->nullable();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('container_number')->nullable();
            $table->string('container_type')->nullable(); // 20ft, 40ft, etc.
            $table->string('vessel_name')->nullable();
            $table->string('voyage')->nullable();
            $table->string('pol')->nullable();
            $table->string('pod')->nullable();
            $table->date('etd')->nullable();
            $table->date('eta')->nullable();
            $table->string('cargo_type')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('volume', 10, 2)->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
