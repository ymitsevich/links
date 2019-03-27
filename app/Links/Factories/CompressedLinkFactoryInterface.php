<?php

namespace App\Links\Factories;


use App\Links\CompressedLinkInterface;
use App\Links\Exceptions\WrongFactoryAttributes;

interface CompressedLinkFactoryInterface
{
    /**
     * @param array $attributes
     * @return CompressedLinkInterface
     * @throws WrongFactoryAttributes
     */
    public function make(array $attributes): CompressedLinkInterface;

}
