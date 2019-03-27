<?php

namespace App\Links\Generators;

/**
 * Interface LinkHashGenerator
 * @package App\Links\Generators
 */
interface LinkHashGenerator
{
    public function getHashByNumber(int $delta);
    public function getNumberByHash(string $hash): int;
}
