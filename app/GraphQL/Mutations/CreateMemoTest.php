<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Image;
use App\Models\MemoTest;

final readonly class CreateMemoTest
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $memoTest = MemoTest::create([
            'name' => $args['input']['name']
        ]);

        foreach ($args['input']['imageUrls'] as $imageUrl) {
            $image = new Image(['url' => $imageUrl]);
            $memoTest->images()->save($image);
        }

        return $memoTest;
    }
}
