<?php

namespace App\Links\Factories;


use App\Links\CompressedLinkInterface;

interface CompressedLinkFactoryInterface
{
    public function make(array $attributes): CompressedLinkInterface;

}
