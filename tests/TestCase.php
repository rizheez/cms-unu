<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var array<int, string>
     */
    private const FORBIDDEN_TEST_DATABASES = [
        'unu_web',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $database = (string) config('database.connections.'.config('database.default').'.database');

        if (in_array($database, self::FORBIDDEN_TEST_DATABASES, true)) {
            throw new RuntimeException("Tests are configured to use the protected database [{$database}]. Use a dedicated testing database instead.");
        }
    }
}
