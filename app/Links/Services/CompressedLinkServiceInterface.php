<?php

namespace App\Links\Services;

use App\Links\CompressedLink;
use App\Links\Exceptions\ErrorSavingLink;
use App\Links\Exceptions\InvalidCompressingLink;
use App\Links\Exceptions\LinkNotFound;
use App\Links\Exceptions\ValidationError;
use App\User;
use Illuminate\Support\Collection;

interface CompressedLinkServiceInterface
{

    public function getAll(): Collection;

    /**
     * @param int $id
     * @return CompressedLink
     * @throws LinkNotFound
     */
    public function get(int $id): CompressedLink;

    /**
     * @param array $attributes
     * @return CompressedLink
     * @throws ErrorSavingLink
     * @throws InvalidCompressingLink
     * @throws ValidationError
     */
    public function store(array $attributes): CompressedLink;

    /**
     * @param int $id
     * @param array $attributes
     * @return CompressedLink
     * @throws ErrorSavingLink
     * @throws LinkNotFound
     * @throws ValidationError
     */
    public function update(int $id, array $attributes): CompressedLink;

    /**
     * @param int $id
     * @return bool
     * @throws LinkNotFound
     */
    public function delete(int $id): bool;

    public function setUser(User $user) : CompressedLinkServiceInterface;

    public function assertValid(CompressedLink $compressedLink);

    /**
     * @param string $hash
     * @return string
     * @throws LinkNotFound
     */
    public function convertToFull(string $hash): string;

    /**
     * @param string $fullLink
     * @return string
     * @throws InvalidCompressingLink
     */
    public function buildCompressed(string $fullLink): string;

}
