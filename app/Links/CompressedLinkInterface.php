<?php

namespace App\Links;


use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface CompressedLinkInterface
{
    public function user(): BelongsTo;

    public function save(array $options = []);

    public function getRules(): array;

    public function getKey();


}
