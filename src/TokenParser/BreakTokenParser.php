<?php

namespace Squirrel\TwigPhpSyntax\TokenParser;

use Twig\Node\Node;

class BreakTokenParser extends BreakOrContinueTokenParser
{
    public function getTag(): string
    {
        return 'break';
    }

    protected function getNodeObject(int $loopNumber, int $lineno): Node
    {
        return new BreakNode($loopNumber, $lineno);
    }
}
