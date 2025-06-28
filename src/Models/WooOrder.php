<?php

namespace BoukjijTarik\WooSales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WooOrder extends Model
{
    protected $connection;
    protected $table;
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'ID',
        'post_author',
        'post_date',
        'post_date_gmt',
        'post_content',
        'post_title',
        'post_excerpt',
        'post_status',
        'comment_status',
        'ping_status',
        'post_password',
        'post_name',
        'to_ping',
        'pinged',
        'post_modified',
        'post_modified_gmt',
        'post_content_filtered',
        'post_parent',
        'guid',
        'menu_order',
        'post_type',
        'post_mime_type',
        'comment_count'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        $this->connection = config('wooSales.database_connection');
        $this->table = config('wooSales.table_prefix') . 'posts';
    }

    /**
     * Scope to filter by order status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('post_status', $status);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('post_date', [$startDate, $endDate]);
    }

    /**
     * Scope to filter by order ID
     */
    public function scopeByOrderId($query, $orderId)
    {
        return $query->where('ID', $orderId);
    }

    /**
     * Get order items for this order
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(WooOrderItem::class, 'order_id', 'ID');
    }

    /**
     * Get order meta data
     */
    public function meta(): HasMany
    {
        return $this->hasMany(WooPostMeta::class, 'post_id', 'ID');
    }
} 