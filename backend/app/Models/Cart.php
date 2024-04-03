<?php

namespace App\Models;

use App\Traits\UUIDAsPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory, UUIDAsPrimaryKey;

    protected $guarded = ['id'];
    protected $table = 'carts';

    // Relationship
    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
