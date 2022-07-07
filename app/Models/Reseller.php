<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reseller extends Model
{
    use HasFactory, SoftDeletes;

    const ACTIVE = "AKTIF";
    const INACTIVE = "NONAKTIF";
    const PENDING = "PENDING";
    const REJECTED = "DITOLAK";

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'shop_name',
        'shop_address',
        'province',
        'city',
        'postal_code',
        'phone_number',
        'social_media',
        'shopee_link',
        'reseller_status',
        'reseller_registration_proof_of_payment',
        'approval_date',
        'approved_by',
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
        'social_media' => 'array'
    ];

    // Relationship
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function approvedBy()
    {
        return $this->hasOne(User::class, 'id', 'approved_by');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'reseller_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'reseller_id');
    }

    // Helper
    public function isActive()
    {
        return $this->approval_date != NULL && $this->reseller_status == self::ACTIVE;
    }

    public function isInactive()
    {
        return $this->reseller_status == self::INACTIVE;
    }

    public function isPending()
    {
        return $this->reseller_registration_proof_of_payment != NULL || $this->reseller_status == self::PENDING;
    }

    public function isRejected()
    {
        return $this->rejection_reason != NULL && $this->reseller_status == self::REJECTED;
    }

    public function phoneNumberWith($prefix = 0)
    {
        return $prefix . ltrim($this->phone_number, '0');
    }

    public function phoneNumberBadge()
    {
        return $this->phone_number . '<br><a href="http://wa.me/' . $this->phoneNumberWith(62) . '" class="badge bg-success text-white"><i class="fab fa-whatsapp"></i> WhatsApp</a>';
    }

    public function statusBadge()
    {
        switch($this->reseller_status) {
            case self::ACTIVE:
                $type = "success";
                $message = self::ACTIVE;
                break;
            case self::INACTIVE:
                $type = "secondary";
                $message = self::INACTIVE;
                break;
            case self::PENDING:
                $type = "warning";
                $message = self::PENDING;
                break;
            case self::REJECTED:
                $type = "danger";
                $message = self::REJECTED;
                break;
            default:
                $type = "dark";
                $message = "Terhapus";
                break;
        }

        $badge = "<span class='badge bg-" . $type . "'>" . $message . "</span>";
        return $badge;
    }

    public function verificationStatus()
    {
        if($this->isPending() || $this->isRejected()) {
            $element = "<select class='form-control' name='reseller_verification_status' id='reseller_verification_status'>";
            $element .= "<option " . ($this->isActive() ? "selected " : "") . "value='" . self::ACTIVE . "' class='form-control'>TERIMA</option>";
            $element .= "<option " . ($this->isRejected() ? "selected " : "") . "value='" . self::REJECTED . "' class='form-control'>TOLAK</option>";
            $element .= "</select>";
        } else {
            $element = $this->reseller_status;
        }

        return $element;
    }

    public function statusSwitchButton()
    {
        $checked = '';
        $disabled = '';
        
        if($this->isActive()) {
            $checked = 'checked';
        }

        if($this->isPending()) {
            $disabled = "disabled";
        }

        $switchButton = "<div class='form-check form-switch'><div class='checkbox'><input $disabled data-id='" . $this->id . "' " . $checked . " " . $disabled . " type='checkbox' class='form-check-input switch-button' name='reseller_status'></div></div>";
        return $switchButton;
    }
}
