<?php


namespace App\Links;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }
}
