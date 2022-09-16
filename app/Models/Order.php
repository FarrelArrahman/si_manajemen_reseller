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
    const DONE = "SELESAI";

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
        'total_price',
        'date',
        'status',
        'admin_notes',
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
        'date'
    ];

    // Relationship
    public function reseller()
    {
        return $this->hasOne(Reseller::class, 'id', 'ordered_by');
    }

    public function admin()
    {
        return $this->hasOne(User::class, 'id', 'handled_by');
    }

    public function orderShipping()
    {
        return $this->belongsTo(OrderShipping::class, 'id', 'order_id');
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function orderPayment()
    {
        return $this->hasOne(OrderPayment::class, 'order_id', 'id');
    }

    // Helpers
    public function isApproved()
    {
        return $this->status == self::APPROVED;
    }

    public function isCanceled()
    {
        return $this->status == self::CANCELED;
    }

    public function isPending()
    {
        return $this->status == self::PENDING;
    }

    public function isRejected()
    {
        return $this->status == self::REJECTED;
    }
    
    public function isDone()
    {
        return $this->status == self::DONE;
    }

    public function statusBadge()
    {
        switch($this->status) {
            case self::APPROVED:
                $type = "primary";
                $message = self::APPROVED;
                break;
            case self::CANCELED:
                $type = "secondary";
                $message = self::CANCELED;
                break;
            case self::PENDING:
                $type = "warning";
                $message = self::PENDING;
                break;
            case self::REJECTED:
                $type = "danger";
                $message = self::REJECTED;
                break;
            case self::DONE:
                $type = "success";
                $message = self::DONE;
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
            $element = "<select class='form-select' id='order_verification_status'>";
            $element .= "<option " . ($this->isApproved() ? "selected " : "") . "value='" . self::APPROVED . "' class='form-control'>TERIMA</option>";
            $element .= "<option " . ($this->isRejected() ? "selected " : "") . "value='" . self::REJECTED . "' class='form-control'>TOLAK</option>";
            $element .= "</select>";
        } else {
            $element = $this->status;
        }

        return $element;
    }
}
