<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use MoonShine\Laravel\Models\MoonshineUserRole;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('moonshine_users', static function (Blueprint $table): void {
            $table->id();

            $table->foreignId('moonshine_user_role_id')
                ->default(MoonshineUserRole::DEFAULT_ROLE_ID)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('email', 190)->unique();
            $table->string('password');
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });

        DB::table('moonshine_users')->insert([
            'id' => MoonshineUserRole::DEFAULT_ROLE_ID,
            'name' => 'Богдан',
            'email' => "gobozovbogdan@gmail.com",
            'password' => Hash::make('Gbr_2003!'),
            'moonshine_user_role_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moonshine_users');
    }
};
