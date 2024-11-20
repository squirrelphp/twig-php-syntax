<?php

namespace Squirrel\TwigPhpSyntax\Tests;

use Squirrel\TwigPhpSyntax\PhpSyntaxExtension;
use Twig\Test\IntegrationTestCase;

class IntegrationTest extends IntegrationTestCase
{
    public function getExtensions(): array
    {
        return [
            new PhpSyntaxExtension(),
        ];
    }

    public static function getFixturesDirectory(): string
    {
        return __DIR__ . '/Fixtures/';
    }
}
