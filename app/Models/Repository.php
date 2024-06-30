<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repository extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable=[
        'file_name',
        'file_type',
        'description',
        'file_path',
        'category',
        'uploaded_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function updater():BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}


