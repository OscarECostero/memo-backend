<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Image;
use App\Models\MemoTest;
use App\Models\GameSession;
use Illuminate\Http\Response;

class MemoTestMutationTest extends TestCase
{
  private $createdMemoTestIds = [];
  private $createdImageId;
  private $createdMemoTestId;
  private $createdGameSessionId;

  /** 
   * @test 
   */
  public function should_create_and_delete_a_memo_test()
  {
    $input = [
      'name' => 'Test MemoTest',
      'imageUrls' => [
        'image1.jpg',
        'image2.jpg',
        'image3.jpg',
      ],
    ];

    $createQuery = '
            mutation CreateMemoTest($input: CreateMemoTestInput!) {
                createMemoTest(input: $input) {
                    id
                    name
                    images {
                        id
                        url
                    }
                }
            }
        ';

    $createResponse = $this->postJson('/graphql', [
      'query' => $createQuery,
      'variables' => [
        'input' => $input,
      ],
    ]);

    $createResponse->assertStatus(Response::HTTP_OK);

    $createdMemoTestId = $createResponse->json('data.createMemoTest.id');

    $this->createdMemoTestIds[] = $createdMemoTestId;

    $deleteQuery = '
            mutation DeleteMemoTest($id: ID!) {
                deleteMemoTest(id: $id)
            }
        ';

    $deleteResponse = $this->postJson('/graphql', [
      'query' => $deleteQuery,
      'variables' => [
        'id' => $createdMemoTestId,
      ],
    ]);

    $deleteResponse->assertStatus(Response::HTTP_OK)
      ->assertJson([
        'data' => [
          'deleteMemoTest' => true,
        ],
      ]);
  }

  /** 
   * @test 
   */
  public function should_create_add_and_remove_image_from_memo_test()
  {
    $createMemoTestInput = [
      'name' => 'Test MemoTest',
      'imageUrls' => [],
    ];

    $createMemoTestQuery = '
            mutation CreateMemoTest($input: CreateMemoTestInput!) {
                createMemoTest(input: $input) {
                    id
                    name
                    images {
                        id
                        url
                    }
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

    $addImageToMemoTestQuery = '
            mutation AddImageToMemoTest($memoTestId: ID!, $imageUrl: String!) {
                addImageToMemoTest(memoTestId: $memoTestId, imageUrl: $imageUrl) {
                    id
                    name
                    images {
                        id
                        url
                    }
                }
            }
        ';

    $addImageToMemoTestResponse = $this->postJson('/graphql', [
      'query' => $addImageToMemoTestQuery,
      'variables' => [
        'memoTestId' => $this->createdMemoTestId,
        'imageUrl' => 'image1.jpg',
      ],
    ]);

    $addImageToMemoTestResponse->assertStatus(Response::HTTP_OK);
    $this->createdImageId = $addImageToMemoTestResponse->json('data.addImageToMemoTest.images.0.id');

    $this->assertTrue($this->createdImageId > 0);

    $removeImageFromMemoTestQuery = '
            mutation RemoveImageFromMemoTest($memoTestId: ID!, $imageId: ID!) {
                removeImageFromMemoTest(memoTestId: $memoTestId, imageId: $imageId) {
                    id
                    name
                    images {
                        id
                        url
                    }
                }
            }
        ';

    $removeImageFromMemoTestResponse = $this->postJson('/graphql', [
      'query' => $removeImageFromMemoTestQuery,
      'variables' => [
        'memoTestId' => $this->createdMemoTestId,
        'imageId' => $this->createdImageId,
      ],
    ]);

    $removeImageFromMemoTestResponse->assertStatus(Response::HTTP_OK);
    $removedImageId = $removeImageFromMemoTestResponse->json('data.removeImageFromMemoTest.images.0.id');

    $updatedMemoTest = MemoTest::with('images')->find($this->createdMemoTestId);
    $this->assertCount(0, $updatedMemoTest->images ?? []);  
  }

  protected function tearDown(): void
  {
    MemoTest::whereIn('id', $this->createdMemoTestIds)->delete();
    Image::where('id', $this->createdImageId)->delete();
    GameSession::where('id', $this->createdGameSessionId)->delete();

    parent::tearDown();
  }
}
