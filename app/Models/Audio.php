<?php

namespace App\Models;

use App\Traits\Attachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    use HasFactory, Attachable;

    protected $appends   = ['real_path', 'path'];
}
