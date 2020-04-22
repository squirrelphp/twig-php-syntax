PHP Syntax for Twig
===================

[![Build Status](https://img.shields.io/travis/com/squirrelphp/twig-php-syntax.svg)](https://travis-ci.com/squirrelphp/twig-php-syntax) ![PHPStan](https://img.shields.io/badge/style-level%207-success.svg?style=flat-round&label=phpstan) [![Packagist Version](https://img.shields.io/packagist/v/squirrelphp/twig-php-syntax.svg?style=flat-round)](https://packagist.org/packages/squirrelphp/twig-php-syntax) [![PHP Version](https://img.shields.io/packagist/php-v/squirrelphp/twig-php-syntax.svg)](https://packagist.org/packages/squirrelphp/twig-php-syntax) [![Software License](https://img.shields.io/badge/license-MIT-success.svg?style=flat-round)](LICENSE)

Enables syntax known from PHP in Twig, so PHP developers can more easily create and edit twig templates. This is especially useful for small projects, where the PHP developers end up writing twig templates and it is not worth it to have a slightly different syntax in your templates.

Installation
------------

    composer require squirrelphp/twig-php-syntax

Configuration
-------------

Add PhpSyntaxExtension to Twig:

```php
$twig = new \Twig\Environment($loader);
$twig->addExtension(new \Squirrel\TwigPhpSyntax\PhpSyntaxExtension());
```

You can also have a look at the extension definition and create your own extension class to only include some of the features, if you do not like all of them.

Features
--------

### === / !== strict comparison operators

Twig has the `same as` operator, or `==` which actually is not the same as `==` in PHP (it is a bit stricter). After being used to `===` and `!==` in PHP it is handy to just them for clarity.

```twig
{% if 1 === 1 %}
{% endif %}

{% if somevariable === "test" %}
{% endif %}

{% if somevariable !== "test" %}
{% endif %}
```

### foreach loops

Twig uses `for` to create loops, with a slightly different syntax compared to `foreach` in PHP. With this library `foreach` becomes available in twig with the same syntax as in PHP:

```twig
{% foreach list as sublist %}
  {% foreach sublist as key => value %}
  {% endforeach %}
{% endforeach %}
```

Internally it behaves the exact same way as `for`: it actually creates ForNode elements, so you have the same functionality like in `for` loops, including the `loop` variable.

### break and continue

Sometimes it can be convenient to break loops in twig, yet there is no native support for it. This library adds `break` and `continue` and they work exactly as in PHP:

```twig
{% foreach list as entry %}
  {% if loop.index > 10 %}
    {% break %}
  {% endif %}
{% endforeach %}
```

You can use `break` with a number to break out of multiple loops, just like in PHP: (`continue` does not support this)

```twig
{% foreach list as sublist %}
  {% foreach sublist as entry %}
    {% if loop.index > 10 %}
      {% break 2 %} {# breaks out of both loops #}
    {% endif %}
  {% endforeach %}
{% endforeach %}
```

While you can often circumvent the usage of `break` and `continue` in twig, it sometimes leads to additional nesting and more complicated code. Just one `break` or `continue` can clarify behavior and intent in these instances. Yet I would advise to use `break` and `continue` sparingly.

### is true / is false tests

Adds a strict true/false test, so expressions become a bit more readable:

```twig
{% if someflag is true %} {# instead of {% if someflag is same as(true) %} #}
{% endif %}

{% if someflag is false %} {# instead of {% if someflag is same as(false) %} #}
{% endif %}
```

### && and ||

If you want to make expressions even more like PHP, you can use `&&` instead of `and` and `||` instead of `or`. This might be the least useful part of this library, as `and` and `or` are already short and clear, yet it is another easily remedied difference between twig and PHP.