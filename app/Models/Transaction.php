<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'customer_name', 'notification_option', 'transactionable_id', 'transactionable_type', 'order_price_per_unit', 'order_quantity','created_by', 'invoice_id', 'invoice_url', 'customer_reference', 'user_defined_field'];
}
