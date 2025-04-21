<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Modules\ChildPanel\App\Models\ChildPanel;
use Modules\ChildPanel\App\Models\ChildPanelService;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'child_panel_id',
        'category_id',
        'service_id',
        'api_order_id',
        'api_refill_id',
        'link',
        'quantity',
        'price',
        'status',
        'refill_status',
        'status_description',
        'reason',
        'comments',
        'agree',
        'start_counter',
        'remains',
        'runs',
        'interval',
        'drip_feed',
        'refilled_at',
        'added_on'
    ];

    protected $dates = [
        'refilled_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function panelService()
    {
        return $this->belongsTo(ChildPanelService::class, 'service_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function childPanel()
    {
        return $this->belongsTo(ChildPanel::class);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public static function boot()
    {
        parent::boot();
        static::saved(function () {
            Cache::forget('orderRecord');
        });
    }


    public function getStatusClass($status)
    {
        $statusClasses = [
            'awaiting' => 'dark',
            'pending' => 'warning',
            'processing' => 'info',
            'progress' => 'primary',
            'completed' => 'success',
            'partial' => 'secondary',
            'canceled' => 'danger',
            'refunded' => 'danger',
            'fail' => 'fail',
        ];
        return $statusClasses[$status] ?? 'text-secondary';
    }
}
