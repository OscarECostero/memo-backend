<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\GameSession;

final readonly class IncrementGameSessionRetries
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $activeGameSession = GameSession::where('memo_test_id', $args['id'])
            ->where('state', GameSession::STATES['started'])
            ->first();

        if ($activeGameSession !== null) {
            $activeGameSession->retries += 1;
            $activeGameSession->save();

        }
        return $activeGameSession;
    }
}
