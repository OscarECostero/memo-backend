<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\GameSession;
use App\Models\MemoTest;

final readonly class EndGameSession
{
  /** @param  array{}  $args */
  public function __invoke(null $_, array $args)
  {
    $gameSession = GameSession::findOrFail($args['id']);
    $gameSession->state = GameSession::STATES['completed'];
    $gameSession->save();
    $score = ($gameSession->number_of_pairs / $gameSession->retries) * 100;
    $roundedScore = round($score);
    $gameSession->score = $roundedScore;

    $memoTest = MemoTest::findOrFail($gameSession->memo_test_id);

    if ($roundedScore > $memoTest->score) {
      $memoTest->score = $roundedScore;
      $memoTest->save();
    }

    return $gameSession;
  }
}
