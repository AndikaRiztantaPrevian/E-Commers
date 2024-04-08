<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];
    protected $table = 'carts';

    // Relationship
    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
