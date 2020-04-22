<?php

namespace Squirrel\TwigPhpSyntax\Test;

use Twig\Compiler;
use Twig\Node\Expression\TestExpression;

/**
 * Checks that a variable is true.
 *
 *  {{ var is true }}
 */
class TrueTest extends TestExpression
{
    public function compile(Compiler $compiler): void
    {
        $compiler
            ->raw('(true === ')
            ->subcompile($this->getNode('node'))
            ->raw(')')
        ;
    }
}
