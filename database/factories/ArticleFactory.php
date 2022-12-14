<?php

/** 
 * @var \Illuminate\Database\Eloquent\Factory $factory 
 **/

use Mpcs\Article\Models\Article;
use Mpcs\Article\Models\ArticleCategory;
use App\Models\User;

use Faker\Generator as Faker;

use Mpcs\Bootstrap5\Bootstrap5;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

$factory->define(Article::class, function (Faker $faker) {
    $title = $faker->sentence();
    $dateBetween = $faker->dateTimeBetween('-30 days', '+30 days', 'ASIA/SEOUL');
    $date = $faker->dateTimeThisMonth;
    $content = $faker->paragraphs(5, true);
    // 이미지 랜덤 생성 FAKER
    // $ratio = ['400', '500', '600', '700', '800', '900'];
    // $aspectRatio = Arr::random($ratio) . "x" . Arr::random($ratio);
    // $image = 'https://via.placeholder.com/' . $aspectRatio . '.jpg';
    // $imageName = round(microtime(true) * 1000) . '_' . Str::random(10) . '.jpg';
    // $manager = new ImageManager(array('driver' => 'gd'));
    // $manager->make($image)->save(storage_path('app/public/uploads/articles/' . $imageName));

    return [
        'article_category_id' => ArticleCategory::inRandomOrder()->first(),
        'title' => $title,
        'summary' => $faker->paragraph(),
        'markdown' => $content,
        'html' => $content,
        //'thumbnail' => Bootstrap5::generateThumb('articles', $imageName),
        'view_count' => mt_rand(0, 1000000),
        'released_at' => function () use ($dateBetween) {
            // 상위 관계를 생성 할 때 체인을 중지 할 확률 50%
            return mt_rand(0, 100) % 2 == 0 ? $dateBetween : null;
        },
        'user_id' => User::inRandomOrder()->first(),
        'created_at' => $date,
        'updated_at' => $date,
        'deleted_at' => function () use ($date) {
            // 상위 관계를 생성 할 때 체인을 중지 할 확률 50%
            return mt_rand(0, 100) % 2 == 0 ? $date : null;
        },
    ];
});
