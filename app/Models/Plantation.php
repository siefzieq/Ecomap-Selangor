<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plantation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable=[
        'plant_id',
        'planting_type',
        'seeding_duration',
        'harvesting_duration',
        'completion_duration',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function plant():BelongsTo
    {
        return $this->belongsTo(plant::class);
    }

    public function updater():BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
