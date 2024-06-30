<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Flat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable=[
        'name',
        'address',
        'longitude',
        'latitude',
        'city',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function expenses():HasMany
    {
        return $this->HasMany(Expenses::class);
    }
    public function plantInventory():HasMany
    {
        return $this->HasMany(plantInventory::class);
    }
    public function progress():HasMany
    {
        return $this->HasMany(FlatProgress::class);
    }
    public function seeding():HasOne
    {
        return $this->hasOne(Flat::class);
    }
}

