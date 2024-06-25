<?php

namespace App\Library\Cache\Contracts;

interface CacheInterface extends CacheMethodInterface
{
    public function uses(CacheMethodInterface $method);

    public function method(): CacheMethodInterface;
}