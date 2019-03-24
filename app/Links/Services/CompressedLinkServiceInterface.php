<?php

namespace App\Links\Services;

use App\Links\CompressedLinkInterface;
use App\Links\Exceptions\LinkNotFound;
use App\Links\Exceptions\ValidationError;
use Illuminate\Support\Collection;

interface CompressedLinkServiceInterface
{

    public function getAll(): Collection;

    /**
     * @param int $id
     * @return CompressedLinkInterface
     * @throws LinkNotFound
     */
    public function get(int $id): CompressedLinkInterface;

    /**
     * @param array $attributes
     * @return CompressedLinkInterface
     * @throws ValidationError
     */
    public function store(array $attributes): CompressedLinkInterface;

    /**
     * @param int $id
     * @param array $attributes
     * @throws ValidationError
     * @return CompressedLinkInterface
     */
    public function update(int $id, array $attributes): CompressedLinkInterface;

    /**
     * @param int $id
     * @return bool
     * @throws LinkNotFound
     */
    public function delete(int $id): bool;

    public function assertValid(CompressedLinkInterface $compressedLink);

}
