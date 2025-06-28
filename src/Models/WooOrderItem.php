<?php

namespace BoukjijTarik\WooSales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WooOrderItem extends Model
{
    protected $connection;
    protected $table;
    protected $primaryKey = 'order_item_id';
    public $timestamps = false;

    protected $fillable = [
        'order_item_id',
        'order_item_name',
        'order_item_type',
        'order_id'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        $this->connection = config('wooSales.database_connection');
        $this->table = config('wooSales.table_prefix') . 'woocommerce_order_items';
    }

    /**
     * Get the order that owns this item
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(WooOrder::class, 'order_id', 'ID');
    }

    /**
     * Get meta data for this order item
     */
    public function meta(): HasMany
    {
        return $this->hasMany(WooOrderItemMeta::class, 'order_item_id', 'order_item_id');
    }

    /**
     * Get product name from meta
     */
    public function getProductNameAttribute()
    {
        return $this->meta()->where('meta_key', '_product_id')->first()?->meta_value ?? $this->order_item_name;
    }

    /**
     * Get line quantity from meta
     */
    public function getQuantityAttribute()
    {
        return $this->meta()->where('meta_key', '_qty')->first()?->meta_value ?? 1;
    }

    /**
     * Get line subtotal from meta
     */
    public function getSubtotalAttribute()
    {
        return $this->meta()->where('meta_key', '_line_subtotal')->first()?->meta_value ?? 0;
    }

    /**
     * Get line discount from meta
     */
    public function getDiscountAttribute()
    {
        return $this->meta()->where('meta_key', '_line_subtotal_tax')->first()?->meta_value ?? 0;
    }
} 