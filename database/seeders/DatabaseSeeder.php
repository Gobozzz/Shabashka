<?php

namespace Database\Seeders;

use App\Enums\RolesUser;
use App\Enums\StatusUser;
use App\Models\City;
use App\Models\Country;
use App\Models\Messenger;
use App\Models\Order;
use App\Models\PaymentPer;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\WorkCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Country::factory(2)->create();
        City::factory(5)->create();
        WorkCategory::factory(10)->create();
        // Рабочий
        $user_worker = User::create([
            'email' => "gobozovbado@gmail.com",
            'phone' => "+79187094256",
            'email_verified_at' => now(),
            'name' => "Богдан",
            'password' => Hash::make("qwerty"),
            'role' => RolesUser::WORKER,
            'status' => StatusUser::ACTIVE,
        ]);
        $user_worker->worker()->create();
        $user_worker->worker->workCategories()->sync(WorkCategory::inRandomOrder()->take(3)->pluck('id')->toArray());
        // Работодатель
        $user_worker = User::create([
            'email' => "gobozovbogdan@gmail.com",
            'phone' => "+79187081980",
            'email_verified_at' => now(),
            'name' => "Артур",
            'password' => Hash::make("qwerty"),
            'role' => RolesUser::EMPLOYER,
            'status' => StatusUser::ACTIVE,
        ]);
        $user_worker->employer()->create();
        // Работодатель 2
        $user_worker = User::create([
            'email' => "rabota@gmail.com",
            'phone' => "+79284847707",
            'email_verified_at' => now(),
            'name' => "Марат",
            'password' => Hash::make("qwerty"),
            'role' => RolesUser::EMPLOYER,
            'status' => StatusUser::ACTIVE,
            'image' => 'example/employer-1.jpg',
        ]);
        $user_worker->employer()->create();
        PaymentPer::factory()->count(4)->sequence(
            [
                'name' => "за час",
            ],
            [
                'name' => "за день",
            ],
            [
                'name' => "за работу",
            ],
            [
                'name' => "в день на человека",
            ],
        )->create();
        Messenger::factory()->count(2)->sequence(
            [
                'name' => "Telegram",
                'image' => "messengers/telegram.png",
                'name_bot' => "@shabashkaSiteBot",
                'link_bot' => "https://t.me/shabashkaSiteBot"
            ],
            [
                'name' => "VK",
                'image' => "messengers/vk.png",
                "name_bot" => "shabashkaVkBot",
                "link_bot" => "https://vk.com/shabashka_bot"
            ],
        )->create();
        Order::factory(40)->create();
    }
}
