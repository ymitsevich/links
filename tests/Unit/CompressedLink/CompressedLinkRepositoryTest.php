<?php

namespace Tests\Unit\CompressedLink;

use App\Links\CompressedLinkInterface;
use App\Links\Exceptions\ErrorSavingModel;
use App\Links\Factories\CompressedLinkFactoryInterface;
use App\Links\Repositories\CompressedLinkRepositoryInterface;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Tests\TestCase;

class CompressedLinkRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    const LINKS_COUNT = 3;

    /**
     * @var User
     */
    private $user;

    /**
     * @var User
     */
    private $wrongUser;

    /**
     * @var CompressedLinkRepositoryInterface
     */
    private $linkRepo;

    /**
     * @var Collection
     */
    private $links;

    /**
     * @var CompressedLinkFactoryInterface
     */
    private $linkFactory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->linkRepo = app(CompressedLinkRepositoryInterface::class);
        $this->linkFactory = app(CompressedLinkFactoryInterface::class);
        $this->user = factory(User::class)->create();
        $this->wrongUser = factory(User::class)->create();
        $this->actingAs($this->user);

        $this->initLinks();
    }

    public function testStore()
    {
        $this->assertCount(self::LINKS_COUNT, $this->links);
    }

    public function testGet()
    {
        $assertedModel = $this->links->first();
        $result = $this->linkRepo->find($assertedModel->id);
        $this->assertEquals($result->link, $assertedModel->link);
        $this->assertTrue($result instanceof CompressedLinkInterface);
    }

    public function testGetAll()
    {
        $result = $this->linkRepo->all();
        $this->assertCount(self::LINKS_COUNT, $result);
        $this->assertTrue($result->first() instanceof CompressedLinkInterface);

    }

    public function testDelete()
    {
        $deletedModel = $this->links->first();

        $result = $this->linkRepo->delete($deletedModel->id);
        $this->assertTrue($result);

        $result = $this->linkRepo->all();
        $this->assertCount(self::LINKS_COUNT - 1, $result);

        $this->expectException(ModelNotFoundException::class);
        $this->linkRepo->find($deletedModel->id);
    }

    public function testUpdate()
    {
        $newValue = 'http://newlink';
        $assertedModel = $this->links->first();

        $compressedLink = $this->linkRepo->find($assertedModel->id);
        $compressedLink->fill(['link' => $newValue]);
        $result = $this->linkRepo->save($compressedLink);

        $this->assertTrue($result instanceof CompressedLinkInterface);

        $result = $this->linkRepo->find($assertedModel->id);
        $this->assertEquals($result->link, $newValue);
    }

    public function testWrongGet()
    {
        $assertedModel = $this->links->first();
        $this->expectException(ModelNotFoundException::class);
        $this->linkRepo->find($assertedModel->id + 1000);
    }

    public function testWrongDelete()
    {
        $deletedModel = $this->links->first();
        $this->expectException(ModelNotFoundException::class);
        $this->linkRepo->delete($deletedModel->id + 1000);
    }

    public function testWrongIdUpdate()
    {
        $newValue = 'http://newlink';
        $assertedModel = $this->links->first();
        $this->expectException(ModelNotFoundException::class);
        $compressedLink = $this->linkRepo->find($assertedModel->id + 1000);
        $compressedLink->fill(['link' => $newValue]);
        $this->linkRepo->save($compressedLink);
    }

    public function testWrongPayloadUpdate()
    {
        $assertedModel = $this->links->first();
        $compressedLink = $this->linkRepo->find($assertedModel->id);
        $compressedLink->fill(['link' => Str::random(10000)]);
        $this->expectException(ErrorSavingModel::class);
        $this->linkRepo->save($compressedLink);
        dump(\DB::table('compressed_links')->get());
    }

    private function initLinks()
    {
        $this->links = collect();
        for ($i = 0; $i < self::LINKS_COUNT; ++$i) {
            $linkModel = $this->linkFactory->make(['link' => "http://testlink{$i}"]);
            $linkModel->user()->associate($this->user);
            $this->links->push($this->linkRepo->save($linkModel));
        }
    }
}

