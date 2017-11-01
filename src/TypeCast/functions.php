<?php

namespace Necronru\Tactitian\Middleware\TypeCast;

function object(string $className, bool $check = false)
{
    if ($check && !class_exists($className)) {
        throw new TypeCastMiddlewareException('Class ' . $className . ' does not exists.');
    }

    return sprintf('object<%s>', $className);
}

/**
 * @param string $type scalar or class
 *
 * @return string
 */
function arrayOf(string $type)
{
    return sprintf('arrayOf<%s>', $type);
}

/**
 * @param string|null $type json, serialize, xml, etc.
 *
 * @return string
 */
function custom(string $type)
{
    return sprintf('custom<%s>', $type);
}