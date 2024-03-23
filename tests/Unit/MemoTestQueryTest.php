<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\MemoTest;
use Illuminate\Http\Response;

class MemoTestQueryTest extends TestCase
{
  private $createdMemoTestIds = [];

  /** @test */
  public function it_returns_all_memotests()
  {
    $memoTests = MemoTest::factory(3)->create();

    foreach ($memoTests as $memoTest) {
      $this->createdMemoTestIds[] = $memoTest->id;
    }

    $query = '
            query {
                getAllMemotests {
                    id
                    name
                    score
                }
            }
        ';

    $response = $this->post('/graphql', [
      'query' => $query,
    ]);

    $response->assertStatus(Response::HTTP_OK);
  }

  /** @test */
  public function it_returns_a_single_memotest_by_id_and_null_if_id_doesnt_exist()
  {
    $memoTest = MemoTest::factory()->create();

    $this->createdMemoTestIds[] = $memoTest->id;

    $query = '
              query GetMemoTest($id: ID!) {
                  memotest(id: $id) {
                      id
                      name
                      score
                  }
              }
          ';

    $response = $this->post('/graphql', [
      'query' => $query,
      'variables' => [
        'id' => $memoTest->id,
      ],
    ]);

    $response->assertStatus(Response::HTTP_OK);

    $response->assertJson([
      'data' => [
        'memotest' => [
          'id' => $memoTest->id,
          'name' => $memoTest->name,
          'score' => $memoTest->score,
        ],
      ],
    ]);

    $queryNonExistent = '
              query GetMemoTestNonExistent($id: ID!) {
                  memotest(id: $id) {
                      id
                      name
                      score
                  }
              }
          ';

    $responseNonExistent = $this->post('/graphql', [
      'query' => $queryNonExistent,
      'variables' => [
        'id' => $memoTest->id + 1,
      ],
    ]);

    $responseNonExistent->assertStatus(Response::HTTP_OK);

    $responseNonExistent->assertJson([
      'data' => [
        'memotest' => null,
      ],
    ]);
  }

  protected function tearDown(): void
  {
    MemoTest::whereIn('id', $this->createdMemoTestIds)->delete();
    parent::tearDown();
  }
}
