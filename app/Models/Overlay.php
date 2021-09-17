<?php

namespace App\Models;

use App\Enum\PictureRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Overlay extends Model
{
    use HasFactory;

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'metadata',
        'type',
    ];

    public function thumbnail(): MorphOne
    {
        return $this->morphOne(Picture::class, 'attachable')->where('roles', PictureRole::THUMBNAIL);
    }

    public function audio(): MorphOne
    {
        return $this->morphOne(Audio::class, 'attachable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
