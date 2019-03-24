<?php

namespace App\Links\Repositories;

use App\Links\CompressedLinkInterface;
use App\Links\Exceptions\ErrorSavingModel;
use App\User;

class CompressedLinkRepository extends BaseRepository implements CompressedLinkRepositoryInterface
{
    /**
     * @var CompressedLinkInterface
     */
    protected $model;

    /**
     * @var User
     */
    protected $user;

    public function __construct(CompressedLinkInterface $model)
    {
        $this->model = $model;
    }

    /**
     * Get name of relationship to be used when using Account
     *
     * @return string
     */
    public function relationship()
    {
        return 'compressedLinks';
    }

    public function all()
    {
        return $this->getModel()->get();
    }

    /**
     * @param CompressedLinkInterface $compressedLink
     * @return CompressedLinkInterface
     * @throws ErrorSavingModel
     */
    public function save(CompressedLinkInterface $compressedLink): CompressedLinkInterface
    {
        try {
            $result = $compressedLink->save();
        } catch (\Exception $e) {
            throw new ErrorSavingModel();
        }

        if (!$result) {
            throw new ErrorSavingModel();
        }

        return $compressedLink;
    }

    public function delete($id)
    {
        return $this->getModel()->findOrFail($id)->delete();
    }

    public function find($id)
    {
        return $this->getModel()->findOrFail($id);
    }

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    public function with($relations)
    {
        return $this->getModel()->with($relations);
    }
}
