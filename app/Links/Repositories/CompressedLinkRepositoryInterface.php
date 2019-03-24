<?php

namespace App\Links\Repositories;

use App\Links\CompressedLinkInterface;
use App\UserInterface;

interface CompressedLinkRepositoryInterface
{
    public function all();

    public function save(CompressedLinkInterface $data): CompressedLinkInterface;

    public function delete(int $id);

    public function find(int $id);

    public function setUser(UserInterface $user);
}
