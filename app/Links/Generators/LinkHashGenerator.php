<?php

namespace App\Links\Generators;

use App\Links\Exceptions\ErrorGeneratingHash;

/**
 * Interface LinkHashGenerator
 * @package App\Links\Generators
 */
interface LinkHashGenerator
{

    /**
     * @param int $delta
     * @return string
     * @throws ErrorGeneratingHash
     */
    public function getHashByNumber(int $delta): string;

    /**
     * @param string $hash
     * @return int
     * @throws ErrorGeneratingHash
     */
    public function getNumberByHash(string $hash): int;
}
