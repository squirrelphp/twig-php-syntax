<?php

namespace Squirrel\TwigPhpSyntax\TokenParser;

class BreakTokenParser extends BreakOrContinueTokenParser
{
    public function getTag(): string
    {
        return 'break';
    }
}
