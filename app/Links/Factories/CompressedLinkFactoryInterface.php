<?php

namespace App\Links\Factories;


use App\Links\CompressedLink;
use App\Links\Exceptions\WrongFactoryAttributes;

interface CompressedLinkFactoryInterface
{
    /**
     * @param array $attributes
     * @return CompressedLink
     * @throws WrongFactoryAttributes
     */
    public function make(array $attributes): CompressedLink;

}
