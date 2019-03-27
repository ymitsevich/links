<?php

namespace Tests\Feature;

use App\Links\Services\CompressedLinkServiceInterface;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

class WebLinkControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var User
     */
    private $user;

    /**
     * @var CompressedLinkServiceInterface
     */
    private $linkService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->linkService = app(CompressedLinkServiceInterface::class);
    }

    public function testProcessWithCompressedLink()
    {
        $assertUrl = 'https://github.com/ymitsevich';
        $fullLink = $this->linkService->setUser($this->user)->buildCompressed($assertUrl);
        $response = $this->get($fullLink);
        $response->assertRedirect($assertUrl);
        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
    }

    public function testWrongLink()
    {
        $assertUrl = 'https://www.youtube.com/watch?v=oHg5SJYRHA0';
        $fullLink = $this->linkService->setUser($this->user)->buildCompressed($assertUrl);
        $fullLink .= 'someartifact';
        $response = $this->get($fullLink);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
