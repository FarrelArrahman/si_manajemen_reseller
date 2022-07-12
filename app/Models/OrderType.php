<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderType extends Model
{
    public $timestamps = false;
    
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'status',
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

    public function orders()
    {
        return $this->hasMany(Order::class, 'order_type_id');
    }

    // Helpers
    public function isShopee()
    {
        return $this->code == "SHP";
    }
    
    public function isExpedition()
    {
        return $this->code == "EXP";
    }

    public function statusBadge()
    {
        $style = '';
        switch($this->code) {
            case "SHP":
                $style = "background: #f60;";
                $type = "default";
                break;
            case "EXP":
                $type = "primary";
                break;
            default:
                $type = "dark";
                break;
        }
        
        $badge = "<span style='$style' class='badge bg-" . $type . "'>" . ($this->name ?? '-') . "</span>";
        return $badge;
    }
}
