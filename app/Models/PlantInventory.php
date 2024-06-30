<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlantInventory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable=[
        'date',
        'plant_id',
        'in_stock',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function updater():BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function plant():BelongsTo
    {
        return $this->belongsTo(plant::class);
    }
    public function flat():BelongsTo
    {
        return $this->belongsTo(flat::class);
    }
    public function seedings():BelongsTo
    {
        return $this->belongsTo(flat::class);
    }

}
