<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_data', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50)->fullText('first_name');
            $table->string('last_name', 50)->fullText('last_name');
            $table->tinyInteger('age')->index();
            $table->enum('gender', ['male', 'female'])->fullText('gender');
            $table->string('mobile_number', 25)->fulltext('mobile_number');
            $table->string('email', 100)->fulltext('email');
            // $table->string('mobile_number', 25)->unique();
            // $table->string('email', 100)->unique();
            $table->string('city', 100)->fulltext('city');
            $table->string('login', 50)->fulltext('login');
            // same as \App\CarModelEnum cases
            $table->enum('car_model', [
                'BMW',
                'Mercedes',
                'Ford',
                'Toyota',
                'Honda',
                'Fiat',
                'Peugeot',
                'Nissan',
                'Volkswagen',
            ])->fulltext('car_model');
            $table->integer('salary')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_data');
    }
};
