<?php

namespace App\models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_id',
        'price',
        'weight',
        'stock_quantity',
        'low_stock_threshold',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'weight' => 'decimal:2',
            'stock_quantity' => 'integer',
            'low_stock_threshold' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<product>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function inStock(): bool
    {
        return $this->is_active && $this->stock_quantity > 0;
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->low_stock_threshold;
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
