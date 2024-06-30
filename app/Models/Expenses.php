<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expenses extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable=[
        'date',
        'flat_id',
        'item_name',
        'category',
        'type',
        'plant_id',
        'amount',
        'quantity',
        'total',
        'file_path',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function flat():BelongsTo
    {
        return $this->belongsTo(Flat::class,'flat_id');
    }
}

