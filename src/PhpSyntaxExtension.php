<?php

namespace Squirrel\TwigPhpSyntax;

use Squirrel\TwigPhpSyntax\ExpressionParser\BinaryOperatorExpressionParser;
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
                        'Given time string for strtotime seems to be invalid: ' . $time,
                    );
                }

                return $timestamp;
            }),
            new TwigFilter('intval', function (mixed $var): int {
                if (\is_int($var)) {
                    return $var;
                }

                $var = $this->validateType($var, 'intval');

                return \intval($var);
            }),
            new TwigFilter('floatval', function (mixed $var): float {
                if (\is_float($var)) {
                    return $var;
                }

                $var = $this->validateType($var, 'floatval');

                return \floatval($var);
            }),
            new TwigFilter('strval', function (mixed $var): string {
                if (\is_string($var)) {
                    return $var;
                }

                $var = $this->validateType($var, 'strval');

                return \strval($var);
            }),
            new TwigFilter('boolval', function (mixed $var): bool {
                if (\is_bool($var)) {
                    return $var;
                }

                $var = $this->validateType($var, 'boolval');

                return \boolval($var);
            }),
        ];
    }

    private function validateType(mixed $var, string $functionName): string|int|float|bool|null
    {
        if (\is_object($var) && \method_exists($var, '__toString')) {
            return $var->__toString();
        }

        if (!\is_scalar($var) && $var !== null) {
            throw new \InvalidArgumentException(
                'Non-scalar value given to ' . $functionName . ' filter',
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

    public function getExpressionParsers(): array
    {
        return [
            new BinaryOperatorExpressionParser(OrBinary::class, '||', 10),
            new BinaryOperatorExpressionParser(AndBinary::class, '&&', 15),
            new BinaryOperatorExpressionParser(SameAsBinary::class, '===', 20),
            new BinaryOperatorExpressionParser(NotSameAsBinary::class, '!==', 20),
        ];
    }
}
