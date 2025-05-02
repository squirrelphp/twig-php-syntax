<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Method Squirrel\\\\TwigPhpSyntax\\\\PhpSyntaxExtension\\:\\:getExpressionParsers\\(\\) return type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/PhpSyntaxExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Squirrel\\\\TwigPhpSyntax\\\\PhpSyntaxExtension\\:\\:validateType\\(\\) should return bool\\|float\\|int\\|string\\|null but returns mixed\\.$#',
	'identifier' => 'return.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/PhpSyntaxExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot cast mixed to int\\.$#',
	'identifier' => 'cast.int',
	'count' => 1,
	'path' => __DIR__ . '/../src/TokenParser/BreakOrContinueTokenParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$name of class Twig\\\\Node\\\\Expression\\\\Variable\\\\AssignContextVariable constructor expects string, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 4,
	'path' => __DIR__ . '/../src/TokenParser/ForeachTokenParser.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$subject of function preg_match expects string, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/TokenParser/ForeachTokenParser.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
