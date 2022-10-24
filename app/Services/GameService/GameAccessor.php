<?php

namespace App\Services\GameService;

use App\Models\Game;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class GameAccessor
{
    public function index(): Collection
    {
        return Game::all();
    }

    public function create(): Model
    {
        $game = Game::create();
        $game->board()->create();
        return $game;
    }

    public function get(string $uuid)
    {
        return Game::with('board')->findOrFail($uuid);
    }

    public function setPiece(string $uuid, string $piece, array $validated)
    {
        $game = Game::findOrFail($uuid);
        $game->board()->update([
            sprintf('%s,%s', $validated['x'], $validated['y']) => $piece
        ]);
    }

    public function updateVictory(Model $game, string $victory): void
    {
        $game->update([
            'victory' => $victory
        ]);
        $game->increment(sprintf('score_%s', $game->victory));
    }

    public function updateTurn(Model $game, string $piece)
    {
        $game->update([
            'current_turn' => $piece,
        ]);
    }

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
