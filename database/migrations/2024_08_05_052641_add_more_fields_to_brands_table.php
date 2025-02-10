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
        Schema::table('brands', function (Blueprint $table) {
            $table->longText('tiktok_link')->nullable();
            $table->longText('fb_social_1')->nullable();
            $table->longText('fb_social_2')->nullable();
            $table->longText('fb_social_3')->nullable();
            $table->longText('tik_social_1')->nullable();
            $table->longText('tik_social_2')->nullable();
            $table->longText('tik_social_3')->nullable();
            $table->longText('ig_social_1')->nullable();
            $table->longText('ig_social_2')->nullable();
            $table->longText('ig_social_3')->nullable();
            $table->longText('youtube_social_1')->nullable();
            $table->longText('youtube_social_2')->nullable();
            $table->longText('youtube_social_3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('tiktok_link');
            $table->dropColumn('fb_social_1');
            $table->dropColumn('fb_social_2');
            $table->dropColumn('fb_social_3');
            $table->dropColumn('tik_social_1');
            $table->dropColumn('tik_social_2');
            $table->dropColumn('tik_social_3');
            $table->dropColumn('ig_social_1');
            $table->dropColumn('ig_social_2');
            $table->dropColumn('ig_social_3');
            $table->dropColumn('youtube_social_1');
            $table->dropColumn('youtube_social_2');
            $table->dropColumn('youtube_social_3');
        });
    }
};
