<?php

namespace App\Links\Services;

use App\Links\Exceptions\ErrorGeneratingHash;
use App\Links\Exceptions\ErrorSavingLink;
use App\Links\Exceptions\ErrorSavingModel;
use App\Links\Exceptions\InvalidCompressingLink;
use App\Links\Exceptions\ValidationError;
use App\Links\Exceptions\WrongFactoryAttributes;
use App\Links\Generators\LinkHashGenerator;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Links\CompressedLink;
use App\Links\Exceptions\LinkNotFound;
use App\Links\Factories\CompressedLinkFactoryInterface;
use App\Links\Repositories\CompressedLinkRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class CompressedLinkService implements CompressedLinkServiceInterface
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var CompressedLinkFactoryInterface
     */
    private $modelFactory;

    /**
     * @var LinkHashGenerator
     */
    private $hashGenerator;

    /**
     * @var CompressedLink
     */
    private $compressedLinkBuilder;

    public function __construct(
        CompressedLinkFactoryInterface $modelFactory,
        LinkHashGenerator $hashGenerator,
        CompressedLink $compressedLink
    )
    {
        $this->modelFactory = $modelFactory;
        $this->hashGenerator = $hashGenerator;
        $this->compressedLinkBuilder = $compressedLink;
    }

    public function getAll(): Collection
    {
        return $this->getBuilder()->get();
    }

    /**
     * @param int $id
     * @return CompressedLink
     * @throws LinkNotFound
     */
    public function get(int $id): CompressedLink
    {
        try {
            $compressedLink = $this->getBuilder()->find($id);
        } catch (ModelNotFoundException $e) {
            throw new LinkNotFound();
        }

        if (!$compressedLink) {
            throw new LinkNotFound();
        }

        return $compressedLink;
    }

    /**
     * @param array $attributes
     * @return CompressedLink
     * @throws ErrorSavingLink
     * @throws InvalidCompressingLink
     * @throws ValidationError
     */
    public function store(array $attributes): CompressedLink
    {
        try {
            $compressedLink = $this->modelFactory->make($attributes);
        } catch (WrongFactoryAttributes $e) {
            throw new InvalidCompressingLink();
        }

        $this->assertValid($compressedLink);
        $compressedLink->user()->associate($this->user);

        try {
            $compressedLink->save();
        } catch (\Exception $e) {
            throw new ErrorSavingLink();

        }

        return $compressedLink;
    }

    /**
     * @param int $id
     * @param array $attributes
     * @return CompressedLink
     * @throws ErrorSavingLink
     * @throws LinkNotFound
     * @throws ValidationError
     */
    public function update(int $id, array $attributes): CompressedLink
    {
        try {
            /** @var CompressedLink $compressedLink */
            $compressedLink = $this->getBuilder()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new LinkNotFound();

        }

        $compressedLink->fill($attributes);
        $this->assertValid($compressedLink);

        try {
            $compressedLink->save();
        } catch (\Exception $e) {
            throw new ErrorSavingLink();

        }

        return $compressedLink;
    }

    /**
     * @param int $id
     * @return bool
     * @throws LinkNotFound
     */
    public function delete(int $id): bool
    {
        try {
            /** @var CompressedLink $compressedLink */
            $compressedLink = $this->getBuilder()->findOrFail($id);
            $result = $compressedLink->delete();
        } catch (ModelNotFoundException $e) {
            throw new LinkNotFound();
        }

        if (!$result) {
            throw new LinkNotFound();
        }

        return true;
    }

    /**
     * @param CompressedLink $compressedLink
     * @throws ValidationError
     */
    public function assertValid(CompressedLink $compressedLink)
    {
        $validator = Validator::make($compressedLink->getAttributes(), $compressedLink->getRules());
        if (!$validator->passes()) {
            throw new ValidationError((string)$validator->getMessageBag());
        }
    }

    public function setUser(User $user): CompressedLinkServiceInterface
    {
        $this->user = $user;
        $this->compressedLinkBuilder = $this->getBuilder()->where('user_id', $user->id);
        return $this;
    }

    /**
     * @param string $hash
     * @return string
     * @throws LinkNotFound
     */
    public function convertToFull(string $hash): string
    {
        try {
            $id = $this->hashGenerator->getNumberByHash($hash);
            /** @var CompressedLink $compressedLink */
            $linkModel = $this->compressedLinkBuilder->find($id);
        } catch (ModelNotFoundException|ErrorGeneratingHash $e) {
            throw new LinkNotFound();
        }

        if (!$linkModel) {
            throw new LinkNotFound();
        }

        return $linkModel->link;
    }

    /**
     * @param string $fullLink
     * @return string
     * @throws ErrorGeneratingHash
     * @throws InvalidCompressingLink
     * @throws ValidationError
     */
    public function buildCompressed(string $fullLink): string
    {
        try {
            $compressedLink = $this->modelFactory->make(['link' => $fullLink]);
            $compressedLink->user()->associate($this->user);
            $this->assertValid($compressedLink);
            $compressedLink->save();
        } catch (WrongFactoryAttributes|ErrorSavingModel $e) {
            throw new InvalidCompressingLink();
        }

        $hash = $this->hashGenerator->getHashByNumber($compressedLink->getKey());

        return $this->buildCompressedUrl($hash);

    }

    private function buildCompressedUrl(string $hash): string
    {
        return config('compressor.domain') . '/' . $hash;
    }

    public function getBuilder()
    {
        return clone $this->compressedLinkBuilder;
    }

}
