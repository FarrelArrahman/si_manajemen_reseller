<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];

    // Relationship
    public function reseller()
    {
        return $this->hasOne(Reseller::class, 'user_id', 'id');
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ?? 'public/user-default.png';
    }

    // Helper
    public function statusBadge()
    {
        $type = 'secondary';
        $message = "Tidak Aktif";
        
        if($this->status == 1) {
            $type = "success";
            $message = "Aktif";
        }

        $badge = "<span class='badge bg-" . $type . "'>" . $message . "</span>";
        return $badge;
    }

    public function statusSwitchButton()
    {
        $checked = '';
        $disabled = '';
        
        if($this->status == 1) {
            $checked = 'checked';
        }

        $switchButton = "<div class='form-check form-switch'><div class='checkbox'><input data-id='" . $this->id . "' " . $checked . " " . $disabled . " type='checkbox' class='form-check-input switch-button' name='status'></div></div>";
        return $switchButton;
    }

    public function isActive() 
    {
        return $this->status == 1;
    }
    
    public function isAdmin() 
    {
        return $this->role == "Admin";
    }

    public function isReseller() 
    {
        return $this->role == "Reseller";
    }
}
