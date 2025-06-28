<?php

namespace BoukjijTarik\WooSales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WooPostMeta extends Model
{
    protected $connection;
    protected $table;
    protected $primaryKey = 'meta_id';
    public $timestamps = false;

    protected $fillable = [
        'meta_id',
        'post_id',
        'meta_key',
        'meta_value'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        $this->connection = config('wooSales.database_connection');
        $this->table = config('wooSales.table_prefix') . 'postmeta';
    }

    /**
     * Get the post that owns this meta
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(WooOrder::class, 'post_id', 'ID');
    }
} 