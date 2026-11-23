<?php

namespace Tests;

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        foreach ([
            ValidateCsrfToken::class,
            PreventRequestForgery::class,
            VerifyCsrfToken::class,
        ] as $middleware) {
            if (class_exists($middleware)) {
                $this->withoutMiddleware($middleware);
            }
        }
    }
}
