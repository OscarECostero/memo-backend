<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\MemoTest;
use App\Models\GameSession;
use Illuminate\Http\Response;

class GameSessionTest extends TestCase
{
  private $createdMemoTestId;
  private $createdGameSessionId;

  /**
   * @test
   */
  public function should_create_and_end_game_session()
  {
    $createMemoTestInput = [
      'name' => 'Test MemoTest',
      'imageUrls' => [],
    ];

    $createMemoTestQuery = '
        mutation CreateMemoTest($input: CreateMemoTestInput!) {
            createMemoTest(input: $input) {
                id
            }
        }
    ';

    $createMemoTestResponse = $this->postJson('/graphql', [
      'query' => $createMemoTestQuery,
      'variables' => [
        'input' => $createMemoTestInput,
      ],
    ]);

    $createMemoTestResponse->assertStatus(Response::HTTP_OK);
    $this->createdMemoTestId = $createMemoTestResponse->json('data.createMemoTest.id');
    $createdMemoTest = MemoTest::find($this->createdMemoTestId);

    $this->assertNotNull($createdMemoTest);

    $createGameSessionInput = [
      'memo_test_id' => $this->createdMemoTestId,
      'retries' => 0,
      'numberOfPairs' => 4,
      'state' => 'Started',
    ];

    $createGameSessionQuery = '
        mutation CreateGameSession($input: CreateGameSessionInput!) {
            createGameSession(input: $input) {
                id
                memo_test_id
                retries
                number_of_pairs
                state
                score
            }
        }
    ';

    $createGameSessionResponse = $this->postJson('/graphql', [
      'query' => $createGameSessionQuery,
      'variables' => [
        'input' => $createGameSessionInput,
      ],
    ]);

    $createGameSessionResponse->assertStatus(Response::HTTP_OK);

    $this->assertEquals(1, $createdMemoTest->gameSession()->count());

    $endGameSessionQuery = '
        mutation EndGameSession($id: ID!) {
            endGameSession(id: $id) {
                id
                state
            }
        }
    ';

    $endGameSessionResponse = $this->postJson('/graphql', [
      'query' => $endGameSessionQuery,
      'variables' => [
        'id' => $createGameSessionResponse->json('data.createGameSession.id'),
      ],
    ]);

    $endGameSessionResponse->assertStatus(Response::HTTP_OK);

    $this->assertEquals('Completed', $createdMemoTest->gameSession->state);
  }
  /**
   * @test
   */
  public function should_increment_game_session_retries()
  {
    $createMemoTestInput = [
      'name' => 'Test MemoTest',
      'imageUrls' => [],
    ];

    $createMemoTestQuery = '
        mutation CreateMemoTest($input: CreateMemoTestInput!) {
            createMemoTest(input: $input) {
                id
            }
        }
    ';

    $createMemoTestResponse = $this->postJson('/graphql', [
      'query' => $createMemoTestQuery,
      'variables' => [
        'input' => $createMemoTestInput,
      ],
    ]);

    $createMemoTestResponse->assertStatus(Response::HTTP_OK);
    $createdMemoTest = MemoTest::find($createMemoTestResponse->json('data.createMemoTest.id'));

    $createGameSessionInput = [
      'memo_test_id' => $createdMemoTest->id,
      'retries' => 0,
      'numberOfPairs' => 4,
      'state' => 'Started',
    ];

    $createGameSessionQuery = '
        mutation CreateGameSession($input: CreateGameSessionInput!) {
            createGameSession(input: $input) {
                id
            }
        }
    ';

    $createGameSessionResponse = $this->postJson('/graphql', [
      'query' => $createGameSessionQuery,
      'variables' => [
        'input' => $createGameSessionInput,
      ],
    ]);

    $createGameSessionResponse->assertStatus(Response::HTTP_OK);
    $this->createdGameSessionId = $createGameSessionResponse->json('data.createGameSession.id');

    $incrementGameSessionRetriesQuery = '
        mutation IncrementGameSessionRetries($id: ID!) {
            incrementGameSessionRetries(id: $id) {
                id
                retries
            }
        }
    ';

    $incrementGameSessionRetriesResponse = $this->postJson('/graphql', [
      'query' => $incrementGameSessionRetriesQuery,
      'variables' => [
        'id' => $createdMemoTest->id,
      ],
    ]);
    $gameSession = GameSession::find($this->createdGameSessionId);

    $incrementGameSessionRetriesResponse->assertStatus(Response::HTTP_OK);

    $this->assertEquals(1, $gameSession->retries);
  }

  protected function tearDown(): void
  {
    MemoTest::where('id', $this->createdMemoTestId)->delete();
    GameSession::where('id', $this->createdGameSessionId)->delete();

    parent::tearDown();
  }
}
