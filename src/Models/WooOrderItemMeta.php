<?php

namespace BoukjijTarik\WooSales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WooOrderItemMeta extends Model
{
    protected $connection;
    protected $table;
    protected $primaryKey = 'meta_id';
    public $timestamps = false;

    protected $fillable = [
        'meta_id',
        'order_item_id',
        'meta_key',
        'meta_value'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        $this->connection = config('wooSales.database_connection');
        $this->table = config('wooSales.table_prefix') . 'woocommerce_order_itemmeta';
    }

    /**
     * Get the order item that owns this meta
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(WooOrderItem::class, 'order_item_id', 'order_item_id');
    }
} 