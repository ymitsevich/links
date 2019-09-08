<?php

namespace Tests\Unit\CompressedLink;

use App\Links\CompressedLink;
use App\Links\Exceptions\LinkNotFound;
use App\Links\Exceptions\ValidationError;
use App\Links\Services\CompressedLinkServiceInterface;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CompressedLinkServiceTest extends TestCase
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
     * @var CompressedLinkServiceInterface
     */
    private $linkService;

    /**
     * @var Collection
     */
    private $links;

    protected function setUp(): void
    {
        parent::setUp();
        $this->linkService = app(CompressedLinkServiceInterface::class);
        $this->user = factory(User::class)->create();
        $this->wrongUser = factory(User::class)->create();
        $this->actingAs($this->user);
        $this->linkService->setUser($this->user);
        $this->initLinks();
    }

    public function testStore()
    {
        $this->assertCount(self::LINKS_COUNT, $this->links);
    }

    public function testGet()
    {
        $assertedModel = $this->links->first();
        $result = $this->linkService->get($assertedModel->id);
        $this->assertEquals($result->link, $assertedModel->link);
        $this->assertTrue($result instanceof CompressedLink);
    }

    public function testGetAll()
    {
        $result = $this->linkService->getAll();
        $this->assertCount(self::LINKS_COUNT, $result);
        $this->assertTrue($result->first() instanceof CompressedLink);

    }

    public function testDelete()
    {
        $deletedModel = $this->links->first();

        $result = $this->linkService->delete($deletedModel->id);
        $this->assertTrue($result);

        $result = $this->linkService->getAll();
        $this->assertCount(self::LINKS_COUNT - 1, $result);

        $this->expectException(LinkNotFound::class);
        $this->linkService->get($deletedModel->id);
    }

    public function testUpdate()
    {
        $newValue = 'http://newlink';
        $assertedModel = $this->links->first();

        $result = $this->linkService->update($assertedModel->id, ['link' => $newValue]);
        $this->assertTrue($result instanceof CompressedLink);

        $result = $this->linkService->get($assertedModel->id);
        $this->assertEquals($result->link, $newValue);
    }

    public function testWrongGet()
    {
        $assertedModel = $this->links->first();
        $this->expectException(LinkNotFound::class);
        $this->linkService->get($assertedModel->id + 1000);
    }

    public function testWrongDelete()
    {
        $deletedModel = $this->links->first();
        $this->expectException(LinkNotFound::class);
        $this->linkService->delete($deletedModel->id + 1000);
    }

    public function testWrongIdUpdate()
    {
        $newValue = 'http://newlink';
        $assertedModel = $this->links->first();
        $this->expectException(LinkNotFound::class);
        $this->linkService->update($assertedModel->id + 1000, ['link' => $newValue]);
    }

    public function testWrongPayloadUpdate()
    {
        $newValue = 'http://newlink';
        $assertedModel = $this->links->first();
        $this->expectException(ValidationError::class);
        $this->linkService->update($assertedModel->id, ['link' => str_repeat($newValue, 1000)]);
    }

    public function testWrongUser()
    {
        $assertedModel = $this->links->first();
        $this->expectException(LinkNotFound::class);
        $this->linkService->setUser($this->wrongUser)->get($assertedModel->id);
    }

    private function initLinks()
    {
        $this->links = collect();
        for ($i = 0; $i < self::LINKS_COUNT; ++$i) {
            $this->links->push($this->linkService->store(['link' => "http://testlink{$i}"]));
        }
    }
}
