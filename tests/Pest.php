<?php

declare(strict_types=1);

use Droath\PluginManager\Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| Bind Pest test files to our base PHPUnit test case so helpers and setup
| logic can be reused across Feature or Unit directories.
|
*/

uses(TestCase::class)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain
| conditions. The "expect()" function gives you access to a set of
| "expectations" methods that you can use to assert different things.
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
| Project-specific helper functions can be exposed here for easy reuse.
|
*/

function something(): void
{
    // ..
}
