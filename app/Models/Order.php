<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'reseller_id',
        'handled_by',
        'notes',
        'discount',
        'address',
        'province',
        'city',
        'zip_code',
        'order_shipping_type_id',
        'date',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        // 
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // 
    ];

    // Relationship
    public function reseller()
    {
        return $this->hasOne(Reseller::class, 'id', 'reseller_id');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'id', 'handled_by');
    }

    public function shippingType()
    {
        return $this->hasOne(OrderShippingType::class, 'id', 'order_shipping_type');
    }
}
