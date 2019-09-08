<?php

namespace App\Links\Repositories;

use App\User;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var User
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

    public function setUser(User $user): BaseRepository
    {
        $this->user = $user;
        return $this;
    }

}
