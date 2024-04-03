<?php

namespace App\Models;

use App\Traits\UUIDAsPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, UUIDAsPrimaryKey;

    protected $guarded = ['id'];
    protected $table = 'categories';

    // Relationship
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
