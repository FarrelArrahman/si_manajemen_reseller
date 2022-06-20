<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'content',
        'created_by',
        'start_from',
        'valid_until',
        'is_private',
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
