<?php 
declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\MemoTest;
use App\Models\Image;

final readonly class RemoveImageFromMemoTest
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $memoTest = MemoTest::findOrFail($args['memoTestId']);
        
        $memoTest->images()->where('id', $args['imageId'])->delete();
        
        return $memoTest;
    }
}
