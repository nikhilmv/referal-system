<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'customer_name', 'phone_no','product_id','quantity','amount'
    ];

    public function product() {
        return $this->belongsTo(Product::class,'product_id');
    }

}
