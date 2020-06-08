PHP Syntax for Twig
===================

[![Build Status](https://img.shields.io/travis/com/squirrelphp/twig-php-syntax.svg)](https://travis-ci.com/squirrelphp/twig-php-syntax) [![Test Coverage](https://api.codeclimate.com/v1/badges/56ed1e15544f2bb7609e/test_coverage)](https://codeclimate.com/github/squirrelphp/twig-php-syntax/test_coverage) ![PHPStan](https://img.shields.io/badge/style-level%208-success.svg?style=flat-round&label=phpstan) [![Packagist Version](https://img.shields.io/packagist/v/squirrelphp/twig-php-syntax.svg?style=flat-round)](https://packagist.org/packages/squirrelphp/twig-php-syntax) [![PHP Version](https://img.shields.io/packagist/php-v/squirrelphp/twig-php-syntax.svg)](https://packagist.org/packages/squirrelphp/twig-php-syntax) [![Software License](https://img.shields.io/badge/license-MIT-success.svg?style=flat-round)](LICENSE)

Enables syntax known from PHP in Twig, so PHP developers can more easily create and edit Twig templates. This is especially useful for small projects, where the PHP developers end up writing Twig templates and it is not worth it to have a slightly different syntax in your templates.

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

### Symfony integration

If you use `autoconfigure` (which is the default) you just need to load the PhpSyntaxExtension class in `services.yaml` in the `config` directory of your project (the first four lines should already be there, just add the line with the PhpSyntaxExtension class at the end of the file):

```yaml
services:
    _defaults:
        autowire: true
        autoconfigure: true

    # Just add the following line, Symfony will register
    # the extension in Twig for you if Twig is installed
    Squirrel\TwigPhpSyntax\PhpSyntaxExtension: ~
```

If you do not use `autoconfigure`, you can add the twig extension tag to the service definition:

```yaml
services:
    Squirrel\TwigPhpSyntax\PhpSyntaxExtension:
        tags:
            - { name: twig.extension }
```

Features
--------

### === / !== strict comparison operators

Twig has the `same as` test, which mimicks `===` in PHP, but has a syntax that can be hard to get used to. Using the strict comparison operators from PHP (`===` and `!==`) reduces friction, is familiar and less verbose.

```twig
{% if 1 === 1 %}
This will be shown
{% endif %}

{% if 1 is same as(1) %}
Same as above but with standard Twig syntax
{% endif %}


{% if 1 === '1' %}
This will not be shown, as 1 and '1' have different types (string vs. integer)
{% endif %}


{% if somevariable === "test" %}
somevariable is of type string and equals "test"
{% endif %}

{% if somevariable !== "test" %}
somevariable either is not a string or does not equal "test"
{% endif %}
```

### strtotime filter

Comparing timestamps in templates when the data only has (date) strings is a bit cumbersome in Twig, as there is no `strtotime` filter - this library adds it exactly as it is in PHP:

```twig
{% if "2018-05-05"|strtotime > "2017-05-05"|strtotime %}
This is always true, as 2018 results in a larger timestamp integer than 2017
{% endif %}

{% if post.date|strtotime > otherpost.date|strtotime %}
Compares the dates of post and otherpost. strtotime returns an integer
or throws an InvalidArgumentException if strtotime returns false
{% endif %}

{# Sets next thursday as a timestamp variable, but also sets "now"
like in strtotime in PHP to define from where the timestamp is
calculated if it is a relative date and not an absolute date #}
{% set nextThusday = "next Thursday"|strtotime(now=sometimestamp) %}
```

### foreach loops

Twig uses `for` to create loops, with a slightly different syntax compared to `foreach` in PHP. With this library `foreach` becomes available in Twig with the same syntax as in PHP:

```twig
{% foreach list as sublist %}
  {% foreach sublist as key => value %}
  {% endforeach %}
{% endforeach %}
```

Internally it behaves the exact same way as `for`: it actually creates ForNode elements, so you have the same functionality like in `for` loops, including [the `loop` variable](https://twig.symfony.com/doc/3.x/tags/for.html#the-loop-variable) and `else`. `else` works the same as with `for`:

```twig
{% foreach list as sublist %}
  {% foreach sublist as key => value %}
  {% else %}
    Array "sublist" is empty / no iteration took place
  {% endforeach %}
{% else %}
  Array "list" is empty / no iteration took place
{% endforeach %}
```

### break and continue

Sometimes it can be convenient to break loops in Twig, yet there is no native support for it. This library adds `break` and `continue` and they work exactly as in PHP:

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
      {% break 2 %} {# breaks out of both foreach loops #}
    {% endif %}
  {% endforeach %}
{% endforeach %}
```

While you can often circumvent the usage of `break` and `continue` in Twig, it sometimes leads to additional nesting and more complicated code. Just one `break` or `continue` can clarify behavior and intent in these instances. Yet I would advise to use `break` and `continue` sparingly.

### Variable type tests (string, array, true, callable, etc.)

Adds tests known from PHP, so you can test a value for being:

 - an array (like `is_array`)
 - a boolean (like `is_bool`)
 - a callable (like `is_callable`)
 - a float (like `is_float`)
 - an integer (like `is_int`)
 - an object (like `is_object`)
 - a scalar (integer, float, string or boolean, like `is_scalar`)
 - a string (like `is_string`)
 - true (like `=== true`)
 - false (like `=== false`)

 It uses the mentioned PHP functions / comparisons internally, so you have the same behavior as in PHP.

```twig
{% if someflag is true %} {# instead of {% if someflag is same as(true) %} #}
{% endif %}

{% if someflag is false %} {# instead of {% if someflag is same as(false) %} #}
{% endif %}

{% if somevar is string %} {# no equivalent in Twig %} #}
{% endif %}

{% if somevar is scalar %} {# no equivalent in Twig %} #}
{% endif %}

{% if somevar is object %} {# no equivalent in Twig %} #}
{% endif %}

{% if somevar is integer %} {# no equivalent in Twig %} #}
{% endif %}
{% if somevar is int %} {# same as integer test above, alternate way to write it %} #}
{% endif %}

{% if somevar is float %} {# no equivalent in Twig %} #}
{% endif %}

{% if somevar is callable %} {# no equivalent in Twig %} #}
{% endif %}

{% if somevar is boolean %} {# no equivalent in Twig %} #}
{% endif %}
{% if somevar is bool %} {# same as boolean test above, alternate way to write it %} #}
{% endif %}

{% if somevar is array %} {# no equivalent in Twig %} #}
{% endif %}
```

### Convert to type: intval, strval, floatval and boolval filters

Converting a variable to a specific type is not something Twig encourages and it probably should be avoided, if possible. Yet there are situations where you just want to convert something to an integer or string so you can be sure a comparison is type safe or that there is no unexpected behavior because one value has the wrong type.

```twig
{% if '5'|intval === 5 %}
Convert '5' to an integer - this if block is being executed
{% endif %}

{% if 5.7|strval === '5.7' %}
Convert 5.7 to a string - this if block is being executed
{% endif %}

{% if 1|boolval === true %}
Convert 1 to a boolean - this if block is being executed
{% endif %}

{% if '5.7'|floatval === 5.7 %}
Convert '5.7' to a float - this if block is being executed
{% endif %}
```

These filters mainly behave like the ones in PHP (and use the corresponding PHP functions internally), but there is some additional behavior to detect or avoid likely errors:

- only scalar values, null and objects with a __toString method are allowed, so if you use any of these filters with an array or an object that cannot be cast to a string it will throw an exception
- null will return 0 for intval, '' for strval, false for boolval and 0.0 for floatval (just like in PHP)
- objects with a __toString method will be converted to a string first (using the __toString method), and only after that intval, boolval and floatval will be used
- boolval should be used with caution, as if you give it any non-numeric string it will return true, yet empty strings and "0" will return false. boolval is here more for completeness, as it is probably the least useful conversion function in PHP. The recommendation is to use the other three functions instead of using boolval if possible.

### && and ||

If you want to make expressions even more like PHP, you can use `&&` instead of `and` and `||` instead of `or`. This might be the least useful part of this library, as `and` and `or` are already short and clear, yet it is another easily remedied difference between Twig and PHP, and `&&` and `||` can be easier to spot in comparison to `and` and `or`.

```twig
{% if someflag === true && otherflag === false %}
instead of if someflag === true and otherflag === false
{% endif %}

{% if someflag === true || otherflag === true %}
instead of if someflag === true or otherflag === false
{% endif %}
```