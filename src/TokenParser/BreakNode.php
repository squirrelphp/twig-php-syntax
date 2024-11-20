<?php

namespace Squirrel\TwigPhpSyntax\TokenParser;

use Twig\Attribute\YieldReady;
use Twig\Compiler;
use Twig\Node\Node;

#[YieldReady]
class BreakNode extends Node
{
    private int $loopNumber = 1;

    public function __construct(int $loopNumber, int $lineno)
    {
        parent::__construct([], [], $lineno);

        $this->loopNumber = $loopNumber;
    }

    public function compile(Compiler $compiler): void
    {
        $compiler
            ->addDebugInfo($this)
            ->write("break " . $this->loopNumber . ";\n")
        ;
    }
}
