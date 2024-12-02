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
       Schema::create('properties', function (Blueprint $table) {
    $table->id();
    $table->enum('type', ['House', 'Apartment']);
    $table->string('address');
    $table->decimal('size', 8, 2); // in SQFT or m2
    $table->integer('bedrooms');
    $table->decimal('price', 10, 2);
    $table->decimal('latitude', 10, 8);
    $table->decimal('longitude', 11, 8);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');

    }
};
