<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetPieceRequest;
use App\Services\GameService\GameService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GameController extends Controller
{
    /**
     * @param GameService $service
     */
    public function __construct(private GameService $service)
    {
    }

    public function index(): Collection
    {
        return $this->service->index();
    }

    public function create(): Model
    {
        return $this->service->create();
    }

    public function get(string $uuid): JsonResponse
    {
        return response()->json($this->service->get($uuid));
    }

    public function setPiece(string $uuid, string $piece, SetPieceRequest $request): JsonResponse
    {
        try {
            $this->service->setPiece($uuid, $piece, $request->validated());
            return response()->json($this->service->get($uuid));
        } catch (HttpException $e) {
            return response()->json($e->getMessage(), $e->getStatusCode());
        }
    }

    public function checkForVictory(string $uuid): void
    {
        $this->service->checkForVictory($uuid);
    }

    public function restart(string $uuid): array
    {
        $this->service->restart($uuid);
        return $this->service->get($uuid);
    }

    public function delete(string $uuid): array
    {
        $this->service->delete($uuid);
        return $this->service->get($uuid);
    }
}
