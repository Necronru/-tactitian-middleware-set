<?php


namespace Necronru\Tactitian\Middleware\TypeCast;


interface ITypeCastableQuery
{
    public function getQuery();

    public function getType(): string;
}