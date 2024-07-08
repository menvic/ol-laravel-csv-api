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

        DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');
        DB::statement('CREATE INDEX idx_first_name_trgm ON user_data USING gin (first_name gin_trgm_ops);');
        DB::statement('CREATE INDEX idx_last_name_trgm ON user_data USING gin (last_name gin_trgm_ops);');
        DB::statement('CREATE INDEX idx_mobile_number_trgm ON user_data USING gin (mobile_number gin_trgm_ops);');
        DB::statement('CREATE INDEX idx_email_trgm ON user_data USING gin (email gin_trgm_ops);');
        DB::statement('CREATE INDEX idx_city_trgm ON user_data USING gin (city gin_trgm_ops);');
        DB::statement('CREATE INDEX idx_login_trgm ON user_data USING gin (login gin_trgm_ops);');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_data');
        DB::statement('DROP EXTENSION IF EXISTS pg_trgm');
    }
};
