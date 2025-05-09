<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected static function api(string $uri): string
    {
        return "/api/{$uri}";
    }
}
