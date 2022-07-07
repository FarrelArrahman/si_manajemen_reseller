<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const APPROVED = "DITERIMA";
    const PENDING = "PENDING";
    const REJECTED = "DITOLAK";
    const CANCELED = "DIBATALKAN";

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'code',
        'ordered_by',
        'handled_by',
        'notes',
        'discount',
        'address',
        'province',
        'city',
        'postal_code',
        'order_type_id',
        'date',
        'status',
        'rejection_reason',
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
