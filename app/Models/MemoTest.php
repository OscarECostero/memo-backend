<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemoTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'images', 'score'
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function gameSession()
    {
        return $this->hasOne(GameSession::class);
    }
}
