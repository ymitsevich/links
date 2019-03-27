<?php

namespace App\Http\Controllers;

use App\Http\Resources\LinkResource;
use App\Links\Exceptions\LinkNotFound;
use App\Links\Exceptions\ValidationError;
use App\Links\Services\CompressedLinkServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiLinkController extends Controller
{

    /**
     * @var CompressedLinkServiceInterface
     */
    private $service;

    public function __construct(CompressedLinkServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $links = $this->service->setUser(auth()->user())->getAll();
        return LinkResource::collection($links);
    }

    public function show(int $id)
    {
        try {
            $link = $this->service->setUser(auth()->user())->get($id);
        } catch (LinkNotFound $e) {
            return response(['status' => 'error', 'data' => 'Requested model not found'], Response::HTTP_NOT_FOUND);
        }

        return new LinkResource($link);

    }

    public function store(Request $request)
    {
        try {
            $link = $this->service->setUser(auth()->user())->store($request->all());
        } catch (ValidationError $e) {
            return response(['status' => 'error', 'data' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new LinkResource($link);
    }

    public function update(Request $request, int $id)
    {
        try {
            $link = $this->service->setUser(auth()->user())->update($id, $request->all());
        } catch (ValidationError $e) {
            return response(['status' => 'error', 'data' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new LinkResource($link);
    }

    public function destroy(int $id)
    {
        try {
            $this->service->setUser(auth()->user())->delete($id);
        } catch (LinkNotFound $e) {
            return response(['status' => 'error', 'data' => 'Requested model not found'], Response::HTTP_NOT_FOUND);
        }

        return response([], Response::HTTP_OK);
    }

}
