<?php

namespace App\Links\Compressors;

/**
 * Interface LinkCompressor
 * @package App\Links\Compressors
 */
interface LinkCompressor
{
    /**
     * @param string $fullLink
     * @return string
     */
    public function build(string $fullLink): string;
}
