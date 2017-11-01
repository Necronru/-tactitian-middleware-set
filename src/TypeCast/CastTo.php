<?php


namespace Necronru\Tactitian\Middleware\TypeCast;

use Necronru\Tactitian\Middleware\TypeCast as Type;

abstract class CastTo
{
    public static function customType($query, string $type)
    {
        return new CastToTypeQuery($query, Type\custom($type));
    }

    public static function array($query)
    {
        return new CastToTypeQuery($query, 'array');
    }

    public static function arrayOf($query, string $type)
    {
        return new CastToTypeQuery($query, Type\arrayOf($type));
    }

    public static function int($query)
    {
        return new CastToTypeQuery($query, 'int');
    }

    public static function float($query)
    {
        return new CastToTypeQuery($query, 'float');
    }

    public static function bool($query)
    {
        return new CastToTypeQuery($query, 'bool');
    }

    public static function string($query)
    {
        return new CastToTypeQuery($query, 'string');
    }

    public static function object($query, string $class)
    {
        return new CastToTypeQuery($query, Type\object($class));
    }

}