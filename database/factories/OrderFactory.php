<?php

namespace Database\Factories;

use App\Enums\PaymentType;
use App\Enums\StatusOrder;
use App\Models\City;
use App\Models\Employer;
use App\Models\Order;
use App\Models\PaymentPer;
use App\Models\WorkCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $images = ['example/order-1.jpg', 'example/order-2.webp', 'example/order-3.jpeg'];
        $paymentTypesArr = [];
        $paymentTypes = PaymentType::cases();
        foreach ($paymentTypes as $paymentType) {
            $paymentTypesArr[] = $paymentType->value;
        }
        $paymentType = $paymentTypesArr[rand(0, count($paymentTypesArr) - 1)];
        return [
            "title" => fake()->text(50),
            "content" => fake()->text(1500),
            "images" => rand(0, 1) ? $images : null,
            "address" => fake()->text(70),
            "city_id" => City::inRandomOrder()->take(1)->first()->id,
            "payment_type" => $paymentType,
            "price" => $paymentType === PaymentType::FIXED->value ? rand(500, 4200) : null,
            "payment_per" => $paymentType === PaymentType::FIXED->value ? PaymentPer::inRandomOrder()->take(1)->first()->name : null,
            "need_count_workers" => rand(0, 1) ? rand(1, 5) : null,
            "status" => StatusOrder::ACTIVE,
            'employer_id' => Employer::inRandomOrder()->first()->id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            $order->workCategories()->sync(WorkCategory::inRandomOrder()->take(3)->pluck('id')->toArray());
        });
    }


}
