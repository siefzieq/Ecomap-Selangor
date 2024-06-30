<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlatProgress extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable=[
        'start_date',
        'expected_date',
        'flat_id',
        'planting_type',
        'plantation_id',
        'area_planted',
        'stage',
        'progress_status',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function plantation():BelongsTo
    {
        return $this->belongsTo(plantation::class);
    }


    public function flat():BelongsTo
    {
        return $this->belongsTo(Flat::class);
    }

    public function updater():BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
