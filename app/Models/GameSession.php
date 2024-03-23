<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    Const STATES = [
        'completed' => 'Completed', 
        'started' => 'Started'
    ];

    use HasFactory;
    
    protected $fillable = [
        'memo_test_id', 'retries', 'number_of_pairs', 'state'
    ];

    protected $casts = [
        'state' => 'string'
    ];

    public function memoTest()
    {
        return $this->belongsTo(MemoTest::class);
    }
}
