<?php
namespace App\Services;


use App\Models\PaymentPer;
use Illuminate\Database\Eloquent\Collection;

class PaymentPerService
{
    public function all(): Collection
    {
        return PaymentPer::all();
    }
}