<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantStockLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'product_variant_id',
        'qty_change',
        'qty_before',
        'qty_after',
        'date',
        'note',
        'handled_by',
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

    /**
     * The date attributes.
     *
     * @var array
     */
    protected $dates = [
        'date'
    ];

    // Relationship
    public function admin()
    {
        return $this->hasOne(Admin::class, 'id', 'restocked_by');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id')->latest();
    }

    public function handledBy()
    {
        return $this->hasOne(User::class, 'id', 'handled_by');
    }
}
