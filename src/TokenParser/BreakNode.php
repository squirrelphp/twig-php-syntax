<?php

namespace Squirrel\TwigPhpSyntax\TokenParser;

use Twig\Attribute\YieldReady;
use Twig\Compiler;
use Twig\Node\Node;

#[YieldReady]
final class BreakNode extends Node
{
    public function __construct(
        private readonly int $loopNumber,
        int $lineno,
    ) {
        parent::__construct([], [], $lineno);
    }

    #[\Override]
    public function compile(Compiler $compiler): void
    {
        $compiler
            ->addDebugInfo($this)
            ->write("break " . $this->loopNumber . ";\n")
        ;
    }
}
