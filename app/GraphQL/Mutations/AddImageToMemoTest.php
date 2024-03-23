<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Image;
use App\Models\MemoTest;

final readonly class AddImageToMemoTest
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $memoTest = MemoTest::findOrFail($args['memoTestId']);

        $image = new Image(['url' => $args['imageUrl']]);
        $memoTest->images()->save($image);

        return $memoTest;
    }
}
