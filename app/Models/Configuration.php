<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'address',
        'province',
        'city',
        'postal_code',
        'customer_service_phone_number',
        'account_number',
        'bank_name',
        'bank_code',
        'account_holder_name',
        'auth_background_image',
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

    protected $dates = [
        'start_from', 'valid_until'
    ];

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    // Helper
    public function statusBadge()
    {
        $type = "success";
        $message = "Publik";
        
        if($this->is_private == 1) {
            $type = 'secondary';
            $message = "Privasi";
        }

        $badge = "<span class='badge bg-" . $type . "'>" . $message . "</span>";
        return $badge;
    }

    public function statusSwitchButton()
    {
        $checked = 'checked';
        
        if($this->is_private == 1) {
            $checked = '';
        }

        $switchButton = "<div class='form-check form-switch'><div class='checkbox'><input data-id='" . $this->id . "' " . $checked . " type='checkbox' class='form-check-input switch-button' name='is_private'></div></div>";
        return $switchButton;
    }
}
