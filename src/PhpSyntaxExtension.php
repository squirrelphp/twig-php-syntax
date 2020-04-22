<?php

namespace Squirrel\TwigPhpSyntax;

use Squirrel\TwigPhpSyntax\Operator\NotSameAsBinary;
use Squirrel\TwigPhpSyntax\Operator\SameAsBinary;
use Squirrel\TwigPhpSyntax\Test\FalseTest;
use Squirrel\TwigPhpSyntax\Test\TrueTest;
use Squirrel\TwigPhpSyntax\TokenParser\BreakTokenParser;
use Squirrel\TwigPhpSyntax\TokenParser\ContinueTokenParser;
use Squirrel\TwigPhpSyntax\TokenParser\ForeachTokenParser;
use Twig\ExpressionParser;
use Twig\Extension\AbstractExtension;
use Twig\Node\Expression\Binary\AndBinary;
use Twig\Node\Expression\Binary\OrBinary;
use Twig\TwigTest;

class PhpSyntaxExtension extends AbstractExtension
{
    public function getTokenParsers(): array
    {
        return [
            new ForeachTokenParser(),
            new BreakTokenParser(),
            new ContinueTokenParser(),
        ];
    }

    public function getTests(): array
    {
        return [
            // adds test: "var is true"
            new TwigTest('true', null, ['node_class' => TrueTest::class]),
            // adds test: "var is false"
            new TwigTest('false', null, ['node_class' => FalseTest::class]),
        ];
    }

    public function getOperators(): array
    {
        return [
            [
            ],
            [
                // instead of "or" the PHP operator "||" does the same
                '||' => ['precedence' => 10, 'class' => OrBinary::class, 'associativity' => ExpressionParser::OPERATOR_LEFT],
                // instead of "and" the PHP operator "&&" does the same
                '&&' => ['precedence' => 15, 'class' => AndBinary::class, 'associativity' => ExpressionParser::OPERATOR_LEFT],
                // instead of "is same as(expression)" it becomes "=== expression"
                '===' => ['precedence' => 20, 'class' => SameAsBinary::class, 'associativity' => ExpressionParser::OPERATOR_LEFT],
                // instead of "is not same as(expression)" it becomes "!== expression"
                '!==' => ['precedence' => 20, 'class' => NotSameAsBinary::class, 'associativity' => ExpressionParser::OPERATOR_LEFT],
            ],
        ];
    }
}
