<?php

namespace App\Links\Repositories;

use App\Links\CompressedLink;
use App\Links\Exceptions\ErrorSavingModel;
use App\User;

interface CompressedLinkRepositoryInterface
{
    public function all();

    /**
     * @param CompressedLink $compressedLink
     * @return CompressedLink
     * @throws ErrorSavingModel
     */
    public function save(CompressedLink $compressedLink): CompressedLink;

    public function delete(int $id);

    public function find(int $id);

    public function setUser(User $user);
}
