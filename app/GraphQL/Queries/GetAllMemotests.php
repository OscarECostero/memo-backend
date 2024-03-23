<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\GameSession;
use App\Models\MemoTest;

final readonly class GetAllMemotests
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $memoTestsWithRelationships = MemoTest::with(['images', 'gameSession' => function ($query) {
            $query->where('state', GameSession::STATES['started']);
        }])->get();
        
        return $memoTestsWithRelationships;
    }
}
