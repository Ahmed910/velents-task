<?php

namespace App\Models;

use App\Traits\FiltersScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Order extends Model
{
    use HasFactory, FiltersScope;

    protected $fillable = ['product_name', 'quantity', 'price', 'status'];

    protected function totalAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->price * $this->quantity,
        );
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}
