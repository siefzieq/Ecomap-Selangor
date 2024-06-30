<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Plant extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'category',
        'description',
        'image_path',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function plantation():HasMany
    {
        return $this->hasMany(plantation::class);
    }

    public function plantInventory():HasMany
    {
        return $this->hasMany(plantInventory::class);
    }

    public function updater():BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function seeding():HasOne
    {
        return $this->hasOne(Seeding::class);
    }

}
