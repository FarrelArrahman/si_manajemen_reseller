<?php

namespace App\Models;

use BinaryCats\Sku\HasSku;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasSku;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'product_name',
        'sku',
        'unit_id',
        'category_id',
        'description',
        'product_status',
        'added_by',
        'last_edited_by',
        'default_photo',
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
        // 
    ];

    // Appends
    public function getDefaultPhotoUrlAttribute() {
        return \Storage::url($this->default_photo);
    }

    // Relationship
    public function unit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
    
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
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
        
        if($this->product_status == 1) {
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
        
        if($this->product_status == 1) {
            $checked = 'checked';
        }

        if($this->trashed()) {
            $disabled = "disabled";
        }

        $switchButton = "<div class='form-check form-switch'><div class='checkbox'><input data-id='" . $this->id . "' " . $checked . " " . $disabled . " type='checkbox' class='form-check-input switch-button' name='product_status'></div></div>";
        return $switchButton;
    }
}
