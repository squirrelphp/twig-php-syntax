<?php

namespace Squirrel\TwigPhpSyntax\ExpressionParser;

use Twig\ExpressionParser\AbstractExpressionParser;
use Twig\ExpressionParser\InfixAssociativity;
use Twig\ExpressionParser\InfixExpressionParserInterface;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Expression\Binary\AbstractBinary;
use Twig\Parser;
use Twig\Token;

final class BinaryOperatorExpressionParser extends AbstractExpressionParser implements InfixExpressionParserInterface
{
    public function __construct(
        /** @var class-string<AbstractBinary> $nodeClass */
        private readonly string $nodeClass,
        private readonly string $name,
        private readonly int $precedence,
        private readonly InfixAssociativity $associativity = InfixAssociativity::Left,
    ) {
    }

    #[\Override]
    public function parse(Parser $parser, AbstractExpression $left, Token $token): AbstractBinary
    {
        $right = $parser->parseExpression($this->getAssociativity() === InfixAssociativity::Left ? $this->getPrecedence() + 1 : $this->getPrecedence());

        return new ($this->nodeClass)($left, $right, $token->getLine());
    }

    #[\Override]
    public function getAssociativity(): InfixAssociativity
    {
        return $this->associativity;
    }

    #[\Override]
    public function getName(): string
    {
        return $this->name;
    }

    #[\Override]
    public function getPrecedence(): int
    {
        return $this->precedence;
    }
}
