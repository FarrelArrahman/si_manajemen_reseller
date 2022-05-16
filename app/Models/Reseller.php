<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reseller extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'reseller_shop_name',
        'reseller_shop_address',
        'province',
        'city',
        'zip_code',
        'phone_number',
        'social_media',
        'shopee_link',
        'reseller_status',
        'reseller_preferences',
        'reseller_approval_date'
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
    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }
}
