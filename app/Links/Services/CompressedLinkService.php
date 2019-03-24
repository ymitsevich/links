<?php

namespace App\Links\Services;

use App\Links\Exceptions\ValidationError;
use Illuminate\Support\Facades\Validator;
use App\Links\CompressedLinkInterface;
use App\Links\Exceptions\LinkNotFound;
use App\Links\Factories\CompressedLinkFactoryInterface;
use App\Links\Repositories\CompressedLinkRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class CompressedLinkService implements CompressedLinkServiceInterface
{

    /**
     * @var CompressedLinkRepositoryInterface
     */
    private $repository;

    /**
     * @var CompressedLinkFactoryInterface
     */
    private $modelFactory;

    public function __construct(
        CompressedLinkRepositoryInterface $repository,
        CompressedLinkFactoryInterface $modelFactory
    )
    {
        $this->repository = $repository->setUser(auth()->user());
        $this->modelFactory = $modelFactory;
    }

    public function getAll(): Collection
    {
        return $this->repository->all();

    }

    /**
     * @param int $id
     * @return CompressedLinkInterface
     * @throws LinkNotFound
     */
    public function get(int $id): CompressedLinkInterface
    {
        try {
            $result = $this->repository->find($id);
        } catch (ModelNotFoundException $e) {
            throw new LinkNotFound();
        }

        if (!$result) {
            throw new LinkNotFound();
        }

        return $result;
    }

    /**
     * @param array $attributes
     * @return CompressedLinkInterface
     * @throws ValidationError
     */
    public function store(array $attributes): CompressedLinkInterface
    {
        /** @var CompressedLinkInterface $compressedLink */
        $compressedLink = $this->modelFactory->make($attributes);
        $this->assertValid($compressedLink);
        $compressedLink->user()->associate(auth()->user());
        return $this->repository->save($compressedLink);
    }

    /**
     * @param int $id
     * @param array $attributes
     * @return CompressedLinkInterface
     * @throws LinkNotFound
     * @throws ValidationError
     */
    public function update(int $id, array $attributes): CompressedLinkInterface
    {
        $compressedLink = $this->repository->find($id);
        if (!$compressedLink) {
            throw new LinkNotFound();
        }
        $compressedLink->fill($attributes);
        $this->assertValid($compressedLink);
        return $this->repository->save($compressedLink);
    }

    /**
     * @param int $id
     * @return bool
     * @throws LinkNotFound
     */
    public function delete(int $id): bool
    {
        try {
            $result = $this->repository->delete($id);
        } catch (ModelNotFoundException $e) {
            throw new LinkNotFound();
        }

        if (!$result) {
            throw new LinkNotFound();
        }

        return true;
    }

    /**
     * @param CompressedLinkInterface $compressedLink
     * @throws ValidationError
     */
    public function assertValid(CompressedLinkInterface $compressedLink)
    {
        $validator = Validator::make($compressedLink->getAttributes(), $compressedLink->getRules());
        if (!$validator->passes()) {
            throw new ValidationError((string)$validator->getMessageBag());
        }
    }
}
