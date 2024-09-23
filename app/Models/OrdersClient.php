<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersClient extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders_client';

    protected $fillable = [
        'client_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'order_id');
    }

}
