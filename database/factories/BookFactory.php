<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Book;
use App\Author;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    $author = Author::all()->random();
    $authorId = $author['id'];
    $authorBirthDate = $author['birth_date'];
    $fiveYearsAfterBirthDate = date('Y-m-d', strtotime($authorBirthDate . '+5 years'));

    return [
        'title' => ucfirst($faker->unique()->words(rand(1,5),true)),
        'author_id' => $authorId,
        'release_date' => $faker->dateTimeBetween($fiveYearsAfterBirthDate, '+10 years')
    ];
});
