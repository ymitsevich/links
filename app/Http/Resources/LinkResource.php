<?php

namespace App\Http\Resources;

use App\Links\Generators\LinkHashGenerator;
use Illuminate\Http\Resources\Json\JsonResource;

class LinkResource extends JsonResource
{
    /**
     * @var LinkHashGenerator
     */
    private $hashGenerator;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->hashGenerator = app(LinkHashGenerator::class);
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \App\Links\Exceptions\ErrorGeneratingHash
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'link' => $this->link,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'short_link' => config('app.url') . '/' . $this->hashGenerator->getHashByNumber($this->id),
        ];
    }
}
