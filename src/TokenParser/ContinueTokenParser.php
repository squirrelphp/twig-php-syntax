<?php

namespace Squirrel\TwigPhpSyntax\TokenParser;

use Twig\Node\Node;

class ContinueTokenParser extends BreakOrContinueTokenParser
{
    public function getTag(): string
    {
        return 'continue';
    }

    protected function getNodeObject(int $loopNumber, int $lineno): Node
    {
        return new ContinueNode($loopNumber, $lineno);
    }
}
