<?php

namespace Squirrel\TwigPhpSyntax\Test;

use Twig\Compiler;
use Twig\Node\Expression\TestExpression;

/**
 * Checks that a variable is a callable.
 *
 *  {{ var is callable }}
 */
final class CallableTest extends TestExpression
{
    #[\Override]
    public function compile(Compiler $compiler): void
    {
        $compiler
            ->raw('(true === \\is_callable(')
            ->subcompile($this->getNode('node'))
            ->raw('))')
        ;
    }
}
