<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

uses()->beforeEach(function () {
    seedDemoData();
})->in('Feature/Frontend', 'Feature/Filament');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function seedDemoData(): void
{
    test()->seed(DatabaseSeeder::class);
}

function loginAsAdmin(): User
{
    $user = User::query()->where('email', 'admin@mosque.test')->firstOrFail();
    test()->actingAs($user);

    return $user;
}

function adminPageUrls(string $resourceClass, ?Model $record = null, bool $canCreate = true): array
{
    $urls = [$resourceClass::getUrl('index')];

    if ($canCreate) {
        $urls[] = $resourceClass::getUrl('create');
    }

    if ($record) {
        $urls[] = $resourceClass::getUrl('edit', ['record' => $record]);
    }

    return $urls;
}

function assertGuestIsRedirectedFrom(array $urls): void
{
    foreach ($urls as $url) {
        test()->get($url)->assertRedirect('/admin/login');
    }
}

function assertAdminCanOpen(array $urls): void
{
    loginAsAdmin();

    foreach ($urls as $url) {
        test()->followingRedirects()->get($url)->assertOk();
    }
}
