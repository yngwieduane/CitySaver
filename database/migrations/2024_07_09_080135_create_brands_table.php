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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')
            ->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->longText('description')->nullable();
            $table->longText('google_map_link')->nullable();
            $table->longText('google_map_business_link')->nullable();
            $table->longText('youtube_link')->nullable();
            $table->longText('facebook_link')->nullable();
            $table->longText('instagram_link')->nullable();
            $table->longText('x_link')->nullable();
            $table->longText('pinterest_link')->nullable();
            $table->longText('website_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
