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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->integer('permission_id')->nullable()->unsigned();
            $table->string('title');
            $table->string('icon');
            $table->string('route')->nullable();
            $table->boolean('header_menu')->nullable()->default(false);
            $table->boolean('sidebar_menu')->nullable()->default(false);
            $table->boolean('dropdown')->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
