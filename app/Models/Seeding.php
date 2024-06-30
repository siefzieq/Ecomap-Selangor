<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seeding extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable=[
        'date',
        'plant_id',
        'flat_id',
        'quantity',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function flat()
    {
        return $this->belongsTo(Flat::class, 'flat_id');
    }

}
