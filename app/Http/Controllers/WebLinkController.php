<?php

namespace App\Http\Controllers;

use App\Links\Exceptions\LinkNotFound;
use App\Links\Services\CompressedLinkServiceInterface;
use Illuminate\Http\Response;

class WebLinkController extends Controller
{

    /**
     * @var CompressedLinkServiceInterface
     */
    private $service;

    public function __construct(CompressedLinkServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @param string $hash
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function process(string $hash)
    {
        try {
            $fullUrl = $this->service->convertToFull($hash);
        } catch (LinkNotFound $e) {
            return response('Requested model not found', Response::HTTP_NOT_FOUND);
        }

        return redirect($fullUrl);
    }

}
