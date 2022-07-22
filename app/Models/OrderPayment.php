<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class OrderPayment extends Model
{
    use HasFactory;

    const NOT_YET = "BELUM";
    const APPROVED = "DITERIMA";
    const PENDING = "PENDING";
    const REJECTED = "DITOLAK";

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'order_id',
        'amount',
        'date',
        'payment_status',
        'proof_of_payment',
        'approved_by',
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

    // Relationship
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function paymentType()
    {
        return $this->hasOne(PaymentType::class, 'id', 'payment_type_id');
    }

    // Helpers
    public function isNotYet()
    {
        return $this->payment_status == self::NOT_YET;
    }

    public function isApproved()
    {
        return $this->payment_status == self::APPROVED;
    }

    public function isPending()
    {
        return $this->payment_status == self::PENDING;
    }

    public function isRejected()
    {
        return $this->payment_status == self::REJECTED;
    }

    public function proofOfPayment()
    {
        if($this->proof_of_payment) {
            $downloadButton = "<a href='" . Storage::url($this->proof_of_payment) . "' class='btn btn-success'><i class='fa fa-download'></i></a>";
        } else {
            $downloadButton = "<i class='fa fa-times text-danger'></i>";
        }
        return $downloadButton;
    }

    public function statusBadge()
    {
        switch($this->payment_status) {
            case self::APPROVED:
                $type = "primary";
                $message = self::APPROVED;
                break;
            case self::PENDING:
                $type = "warning";
                $message = self::PENDING;
                break;
            case self::REJECTED:
                $type = "danger";
                $message = self::REJECTED;
                break;
            case self::NOT_YET:
                $type = "secondary";
                $message = self::NOT_YET;
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
            $element = "<select class='form-control' id='payment_verification_status'>";
            $element .= "<option " . ($this->isApproved() ? "selected " : "") . "value='" . self::APPROVED . "' class='form-control'>TERIMA</option>";
            $element .= "<option " . ($this->isRejected() ? "selected " : "") . "value='" . self::REJECTED . "' class='form-control'>TOLAK</option>";
            $element .= "</select>";
        } else {
            $element = $this->payment_status;
        }

        return $element;
    }
}
