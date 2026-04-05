<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'date_of_birth',
        'gender',
        'total_spent',
        'orders_count',
        'last_ordered_at',
    ];

    protected $casts = [
        'total_spent' => 'decimal:2',
        'orders_count' => 'integer',
        'date_of_birth' => 'date',
        'last_ordered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
