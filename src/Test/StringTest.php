<?php

namespace Squirrel\TwigPhpSyntax\Test;

use Twig\Compiler;
use Twig\Node\Expression\TestExpression;

/**
 * Checks that a variable is a string.
 *
 *  {{ var is string }}
 */
class StringTest extends TestExpression
{
    public function compile(Compiler $compiler): void
    {
        $compiler
            ->raw('(true === \\is_string(')
            ->subcompile($this->getNode('node'))
            ->raw('))')
        ;
    }
}
