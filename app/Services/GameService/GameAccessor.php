<?php

namespace App\Services\GameService;

use App\Models\Game;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class GameAccessor
{
    /**
     * @return Collection
     */
    public function index(): Collection
    {
        return Game::all();
    }

    /**
     * @return Model
     */
    public function create(): Model
    {
        $game = Game::create();
        $game->board()->create();
        return $game;
    }

    /**
     * @param string $uuid
     * @return Model
     */
    public function get(string $uuid): Model
    {
        return Game::with('board')->findOrFail($uuid);
    }

    /**
     * @param string $uuid
     * @param string $piece
     * @param array $validated
     */
    public function setPiece(string $uuid, string $piece, array $validated)
    {
        $game = Game::findOrFail($uuid);
        $game->board()->update([
            sprintf('%s,%s', $validated['x'], $validated['y']) => $piece
        ]);
    }

    /**
     * @param Model $game
     * @param string $victory
     */
    public function updateVictory(Model $game, string $victory): void
    {
        $game->update([
            'victory' => $victory
        ]);
        $game->increment(sprintf('score_%s', $game->victory));
    }

    /**
     * @param Model $game
     * @param string $piece
     */
    public function updateTurn(Model $game, string $piece)
    {
        $game->update([
            'current_turn' => $piece,
        ]);
    }

    /**
     * @param string $uuid
     */
    public function restart(string $uuid)
    {
        $game = Game::findOrFail($uuid);
        $game->board()->delete();
        $game->board()->create();
        $game->update([
            'current_turn' => 'x',
            'victory' => null,
        ]);
    }

    /**
     * @param string $uuid
     */
    public function delete(string $uuid)
    {
        $game = Game::findOrFail($uuid);
        $game->board()->delete();
        $game->board()->create();
        $game->update([
            'current_turn' => 'x',
            'victory' => null,
            'score_x' => 0,
            'score_o' => 0,
        ]);
    }
}
