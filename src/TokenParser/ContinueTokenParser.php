<?php

namespace Squirrel\TwigPhpSyntax\TokenParser;

class ContinueTokenParser extends BreakOrContinueTokenParser
{
    public function getTag(): string
    {
        return 'continue';
    }
}
