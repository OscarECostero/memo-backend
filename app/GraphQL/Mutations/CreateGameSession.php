<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\GameSession;

final readonly class CreateGameSession
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $memoTestId = $args['input']['memo_test_id'];
        $activeGameSession = GameSession::where('memo_test_id', $memoTestId)
            ->where('state', 'Started')
            ->first();

        if ($activeGameSession !== null) {
            return $activeGameSession;
        }

        return GameSession::create([
            'memo_test_id' => $memoTestId,
            'retries' => $args['input']['retries'] ?? 0,
            'number_of_pairs' => $args['input']['numberOfPairs'],
            'state' => $args['input']['state'] ?? 'Started',
        ]);
    }
}
