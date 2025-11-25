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
        Schema::create('settings', function (Blueprint $table) {
          $table->id();
            $table->integer('counter1')->nullable();
            $table->integer('counter2')->nullable();
            $table->integer('counter3')->nullable();
            $table->integer('counter4')->nullable();

            $table->string('photo')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('location')->nullable();

            $table->string('facebook')->nullable();
            $table->string('x')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();

            $table->string('name')->nullable();

            $table->string('ZOOM_ACCOUNT_ID')->nullable();
            $table->string('ZOOM_CLIENT_SECRET')->nullable();
            $table->string('ZOOM_CLIENT_KEY')->nullable();

            $table->string('photo_about')->nullable();
            $table->string('photo_services')->nullable();
            $table->string('photo_products')->nullable();
            $table->string('photo_faq')->nullable();
            $table->string('photo_contact')->nullable();

            $table->string('color')->nullable();
            $table->string('background_color')->nullable();
            $table->string('color_h')->nullable();
            $table->string('color_header')->nullable();

            $table->longText('Blogs')->nullable();
            $table->longText('Story')->nullable();
            $table->longText('About')->nullable();
            $table->longText('Services')->nullable();
            $table->longText('FAQ')->nullable();
            $table->longText('Contact')->nullable();
            $table->longText('Products')->nullable();
            $table->longText('Sessions')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
