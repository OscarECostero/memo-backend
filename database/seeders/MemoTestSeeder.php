<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\MemoTest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemoTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firstMemotest = MemoTest::create([
            'name' => 'Animals',
        ]);
        $secondMemotest = MemoTest::create([
            'name' => 'Emojis',
        ]);

        $image1 = new Image(['url' => 'https://images.pexels.com/photos/20787/pexels-photo.jpg']);
        $image2 = new Image(['url' => 'https://images.pexels.com/photos/164186/pexels-photo-164186.jpeg']);
        $image3 = new Image(['url' => 'https://images.pexels.com/photos/635499/pexels-photo-635499.jpeg']);
        $image4 = new Image(['url' => 'https://images.pexels.com/photos/733998/pexels-photo-733998.jpeg']);
        $image5 = new Image(['url' => 'https://emojicdn.elk.sh/😃?background=%23FFFFFF']);
        $image6 = new Image(['url' => 'https://emojicdn.elk.sh/😍?background=%23FFFFFF']);
        $image7 = new Image(['url' => 'https://emojicdn.elk.sh/😎?background=%23FFFFFF']);
        $image8 = new Image(['url' => 'https://emojicdn.elk.sh/🚀?background=%23FFFFFF']);

        $firstMemotest->images()->saveMany([$image1, $image2, $image3, $image4]);
        $secondMemotest->images()->saveMany([$image5, $image6, $image7, $image8]);
    }
}
