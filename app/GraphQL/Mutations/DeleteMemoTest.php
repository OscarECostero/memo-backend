<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\MemoTest;

final readonly class DeleteMemoTest
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $memoTestId = $args['id'];

        $memoTest = MemoTest::find($memoTestId);

        if (!$memoTest) {
            throw new \Exception("MemoTest not found.");
        }

        $deleted = $memoTest->delete();

        return $deleted;
    }
}
