<?php

namespace Squirrel\TwigPhpSyntax\Tests;

use Squirrel\TwigPhpSyntax\PhpSyntaxExtension;
use Twig\Test\IntegrationTestCase;

class IntegrationTest extends IntegrationTestCase
{
    public function getExtensions()
    {
        return [
            new PhpSyntaxExtension(),
        ];
    }

    public function getFixturesDir()
    {
        return __DIR__ . '/Fixtures/';
    }
}
