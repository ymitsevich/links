<?php

namespace App\Links\Repositories;

use App\Links\CompressedLink;
use App\Links\Exceptions\ErrorSavingModel;
use App\User;

class CompressedLinkRepository extends BaseRepository implements CompressedLinkRepositoryInterface
{
    /**
     * @var CompressedLink
     */
    protected $model;

    /**
     * @var User
     */
    protected $user;

    public function __construct(CompressedLink $model)
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
     * @param CompressedLink $compressedLink
     * @return CompressedLink
     * @throws ErrorSavingModel
     */
    public function save(CompressedLink $compressedLink): CompressedLink
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
