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

    /**
     * @return Collection
     */
    public function index(): Collection
    {
        return $this->service->index();
    }

    /**
     * @return Model
     */
    public function create(): Model
    {
        return $this->service->create();
    }

    /**
     * @param string $uuid
     * @return JsonResponse
     */
    public function get(string $uuid): JsonResponse
    {
        return response()->json($this->service->get($uuid));
    }

    /**
     * @param string $uuid
     * @param string $piece
     * @param SetPieceRequest $request
     * @return JsonResponse
     */
    public function setPiece(string $uuid, string $piece, SetPieceRequest $request): JsonResponse
    {
        try {
            $this->service->setPiece($uuid, $piece, $request->validated());
            return response()->json($this->service->get($uuid));
        } catch (HttpException $e) {
            return response()->json($e->getMessage(), $e->getStatusCode());
        }
    }

    /**
     * @param string $uuid
     */
    public function checkForVictory(string $uuid): void
    {
        $this->service->checkForVictory($uuid);
    }

    /**
     * @param string $uuid
     * @return array
     */
    public function restart(string $uuid): array
    {
        $this->service->restart($uuid);
        return $this->service->get($uuid);
    }

    /**
     * @param string $uuid
     * @return array
     */
    public function delete(string $uuid): array
    {
        $this->service->delete($uuid);
        return $this->service->get($uuid);
    }
}
