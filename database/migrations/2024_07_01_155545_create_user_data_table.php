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
            $table->string('first_name', 50)->index();
            $table->string('last_name', 50)->index();
            $table->tinyInteger('age')->index();
            $table->enum('gender', ['male', 'female'])->index();
            $table->string('mobile_number', 25)->index();
            $table->string('email', 100)->index();
            // $table->string('mobile_number', 25)->unique();
            // $table->string('email', 100)->unique();
            $table->string('city', 100)->index();
            $table->string('login', 50)->index();
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
            ])->index();
            $table->integer('salary')->index();
            $table->timestamps();
        });

        DB::statement('CREATE INDEX user_data_lower_first_name_index ON user_data (LOWER(first_name))');
        DB::statement('CREATE INDEX user_data_lower_last_name_index ON user_data (LOWER(last_name))');
        DB::statement('CREATE INDEX user_data_lower_email_index ON user_data (LOWER(email))');
        DB::statement('CREATE INDEX user_data_lower_city_index ON user_data (LOWER(city))');
        DB::statement('CREATE INDEX user_data_lower_login_index ON user_data (LOWER(login))');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_data');
    }
};
