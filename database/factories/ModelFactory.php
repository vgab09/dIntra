<?php

use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/



$factory->define(\App\Persistence\Models\Designation::class, function (Faker $faker) {
    return [
        'name' => $faker->text(191),
        'description' => $faker->text('400'),
        'active' => $faker->boolean(90),

    ];
});

$factory->define(\App\Persistence\Models\Department::class, function (Faker $faker) {
    return [
        'name' => $faker->text(191),
        'description' => $faker->text('400'),
        'active' => $faker->boolean(90),
    ];
});

$factory->define(\App\Persistence\Models\EmploymentForm::class, function (Faker $faker) {
    return [
        'name' => $faker->colorName,
    ];
});

$factory->define(\App\Persistence\Models\LeaveType::class, function (Faker $faker) {
    return [
        'name' => $faker->domainWord,
    ];
});

$factory->define(\App\Persistence\Models\Holiday::class, function (Faker $faker) {

    $start = Carbon::instance($faker->dateTimeThisYear());

    return [
        'name' => $faker->text(191),
        'start' => $start->format('Y-d-m'),
        'end' => $start->addDays(rand(1,3))->format('Y-d-m'),
        'description' => $faker->text(400),
    ];
});

$factory->define(\App\Persistence\Models\Employee::class, function (Faker $faker) {

    $hiringDate = $faker->date();
    $date = new Carbon($hiringDate);

    return [
        'hiring_date' => $hiringDate,
        'termination_date' => $faker->boolean(20) ? $date->addWeeks(rand(1, 52))->format('Y-m-d') : null,
        'name' => $faker->name(),
        'email' => $faker->unique($faker->email()),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'date_of_birth' => $faker->dateTimeThisCentury($date->subYear(18))->format('Y-m-d'),
        'active' => $faker->boolean(90)
    ];
});