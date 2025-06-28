<?php

namespace BoukjijTarik\WooSales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WooProduct extends Model
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
     * Scope to filter only products
     */
    public function scopeProducts($query)
    {
        return $query->where('post_type', 'product');
    }

    /**
     * Get product meta data
     */
    public function meta(): HasMany
    {
        return $this->hasMany(WooPostMeta::class, 'post_id', 'ID');
    }
} 