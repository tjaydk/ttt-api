<?php

namespace App\Services\GameService;

use Illuminate\Database\Eloquent\Model;

class GameAggregator
{
    public function gameResource(Model $game): array
    {
        return [
            'id' => $game->id,
            'board' => [
                [$game->board['0,0'], $game->board['0,1'], $game->board['0,2']],
                [$game->board['1,0'], $game->board['1,1'], $game->board['1,2']],
                [$game->board['2,0'], $game->board['2,1'], $game->board['2,2']],
            ],
            'score' => [
                'x' => $game->score_x,
                'o' => $game->score_o
            ],
            'currentTurn' => $game->current_turn,
            'victory' => $game->victory,
        ];
    }
}
