<?php

namespace Squirrel\TwigPhpSyntax\Test;

use Twig\Compiler;
use Twig\Node\Expression\TestExpression;

/**
 * Checks that a variable is false.
 *
 *  {{ var is false }}
 */
class FalseTest extends TestExpression
{
    public function compile(Compiler $compiler): void
    {
        $compiler
            ->raw('(false === ')
            ->subcompile($this->getNode('node'))
            ->raw(')')
        ;
    }
}
