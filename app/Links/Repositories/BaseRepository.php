<?php

namespace App\Links\Repositories;

use App\UserInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var UserInterface
     */
    protected $user;

    abstract public function relationship();

    public function getModel()
    {
        if ($this->user) {
            return $this->user->{$this->relationship()}();
        }

        return $this->model;

    }

    public function setUser(UserInterface $user): BaseRepository
    {
        $this->user = $user;
        return $this;
    }

}
