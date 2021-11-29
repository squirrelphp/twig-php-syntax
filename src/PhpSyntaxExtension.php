<?php

namespace Squirrel\TwigPhpSyntax;

use Squirrel\TwigPhpSyntax\Operator\NotSameAsBinary;
use Squirrel\TwigPhpSyntax\Operator\SameAsBinary;
use Squirrel\TwigPhpSyntax\Test\ArrayTest;
use Squirrel\TwigPhpSyntax\Test\BooleanTest;
use Squirrel\TwigPhpSyntax\Test\CallableTest;
use Squirrel\TwigPhpSyntax\Test\FalseTest;
use Squirrel\TwigPhpSyntax\Test\FloatTest;
use Squirrel\TwigPhpSyntax\Test\IntegerTest;
use Squirrel\TwigPhpSyntax\Test\ObjectTest;
use Squirrel\TwigPhpSyntax\Test\ScalarTest;
use Squirrel\TwigPhpSyntax\Test\StringTest;
use Squirrel\TwigPhpSyntax\Test\TrueTest;
use Squirrel\TwigPhpSyntax\TokenParser\BreakTokenParser;
use Squirrel\TwigPhpSyntax\TokenParser\ContinueTokenParser;
use Squirrel\TwigPhpSyntax\TokenParser\ForeachTokenParser;
use Twig\ExpressionParser;
use Twig\Extension\AbstractExtension;
use Twig\Node\Expression\Binary\AndBinary;
use Twig\Node\Expression\Binary\OrBinary;
use Twig\TwigFilter;
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

    public function getFilters(): array
    {
        return [
            new TwigFilter('strtotime', function (string $time, ?int $now = null): int {
                $timestamp = \strtotime($time, $now ?? time());

                if ($timestamp === false) {
                    throw new \InvalidArgumentException(
                        'Given time string for strtotime seems to be invalid: ' . $time
                    );
                }

                return $timestamp;
            }),
            new TwigFilter('intval', /** @param mixed $var */ function ($var): int {
                if (\is_int($var)) {
                    return $var;
                }

                $var = $this->validateType($var);

                return \intval($var);
            }),
            new TwigFilter('floatval', /** @param mixed $var */ function ($var): float {
                if (\is_float($var)) {
                    return $var;
                }

                $var = $this->validateType($var);

                return \floatval($var);
            }),
            new TwigFilter('strval', /** @param mixed $var */ function ($var): string {
                if (\is_string($var)) {
                    return $var;
                }

                $var = $this->validateType($var);

                return \strval($var);
            }),
            new TwigFilter('boolval', /** @param mixed $var */ function ($var): bool {
                if (\is_bool($var)) {
                    return $var;
                }

                $var = $this->validateType($var);

                return \boolval($var);
            }),
        ];
    }

    /**
     * @param mixed $var
     * @return string|int|float|bool|null
     */
    private function validateType($var)
    {
        if (\is_object($var) && \method_exists($var, '__toString')) {
            return $var->__toString();
        }

        if (!\is_scalar($var) && $var !== null) {
            throw new \InvalidArgumentException(
                'Non-scalar value given to intval/floatval/strval/boolval filter'
            );
        }

        return $var;
    }

    public function getTests(): array
    {
        return [
            // adds test: "var is true"
            new TwigTest('true', null, ['node_class' => TrueTest::class]),
            // adds test: "var is false"
            new TwigTest('false', null, ['node_class' => FalseTest::class]),
            // adds test: "var is array"
            new TwigTest('array', null, ['node_class' => ArrayTest::class]),
            // adds test: "var is bool" / "var is boolean"
            new TwigTest('bool', null, ['node_class' => BooleanTest::class]),
            new TwigTest('boolean', null, ['node_class' => BooleanTest::class]),
            // adds test: "var is callable"
            new TwigTest('callable', null, ['node_class' => CallableTest::class]),
            // adds test: "var is float"
            new TwigTest('float', null, ['node_class' => FloatTest::class]),
            // adds test: "var is int" / "var is integer"
            new TwigTest('int', null, ['node_class' => IntegerTest::class]),
            new TwigTest('integer', null, ['node_class' => IntegerTest::class]),
            // adds test: "var is object"
            new TwigTest('object', null, ['node_class' => ObjectTest::class]),
            // adds test: "var is scalar"
            new TwigTest('scalar', null, ['node_class' => ScalarTest::class]),
            // adds test: "var is string"
            new TwigTest('string', null, ['node_class' => StringTest::class]),
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
