<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'product_id',
        'product_variant_name',
        'base_price',
        'general_price',
        'reseller_price',
        'stock',
        'color',
        'photo',
        'product_variant_status',
        'weight',
        'added_by',
        'last_edited_by',
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

    protected $appends = [
        'photo_storage_path'
    ];

    // Appends
    public function getPhotoStoragePathAttribute()
    {
        return \Storage::url($this->photo);
    }

    // Relationship
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function productVariantStockLog()
    {
        return $this->hasMany(ProductVariantStockLog::class, 'product_variant_id', 'id')->orderBy('date', 'DESC');
    }

    public function addedBy()
    {
        return $this->hasOne(User::class, 'id', 'added_by');
    }

    public function lastEditedBy()
    {
        return $this->hasOne(User::class, 'id', 'last_edited_by');
    }

    // Helper
    public function statusBadge()
    {
        $type = 'secondary';
        $message = "Tidak Aktif";
        
        if($this->product_variant_status == 1) {
            $type = "success";
            $message = "Aktif";
        }

        if($this->trashed()) {
            $type = "danger";
            $message = "Dihapus Sementara";
        }

        $badge = "<span class='badge bg-" . $type . "'>" . $message . "</span>";
        return $badge;
    }

    public function statusSwitchButton()
    {
        $checked = '';
        $disabled = '';
        
        if($this->product_variant_status == 1) {
            $checked = 'checked';
        }

        if($this->trashed()) {
            $disabled = "disabled";
        }

        $switchButton = "<div class='form-check form-switch'><div class='checkbox'><input data-id='" . $this->id . "' " . $checked . " " . $disabled . " type='checkbox' class='form-check-input switch-button' name='product_variant_status'></div></div>";
        return $switchButton;
    }
}
