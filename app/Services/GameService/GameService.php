<?php

namespace App\Services\GameService;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GameService
{
    /**
     * @param GameAccessor $accessor
     * @param GameAggregator $aggregator
     */
    public function __construct(private GameAccessor $accessor, private GameAggregator $aggregator)
    {
    }

    /**
     * @return Collection
     */
    public function index(): Collection
    {
        return $this->accessor->index();
    }

    /**
     * @return Model
     */
    public function create(): Model
    {
        return $this->accessor->create();
    }

    /**
     * @param string $uuid
     * @return array
     */
    public function get(string $uuid): array
    {
        $game = $this->accessor->get($uuid);
        return $this->aggregator->gameResource($game);
    }

    /**
     * @param string $uuid
     * @param string $piece
     * @param array $validated
     */
    public function setPiece(string $uuid, string $piece, array $validated): void
    {
        $game = $this->accessor->get($uuid);
        $vector = sprintf('%s,%s', $validated['x'], $validated['y']);

        if ($this->validTurn($game, $piece) && $this->validPosition($game, $vector)) {
            $this->accessor->setPiece($uuid, $piece, $validated);
            $nextTurn = $piece === 'x' ? 'o' : 'x';
            $this->accessor->updateTurn($game, $nextTurn);
        }
    }

    /**
     * @param Model $game
     * @param string $piece
     * @return bool
     */
    private function validTurn(Model $game, string $piece): bool
    {
        $validTurn = $game->current_turn === $piece;
        if (!$validTurn) {
            throw new HttpException(406, 'Not your turn');
        }
        return $validTurn;
    }

    /**
     * @param Model $game
     * @param string $vector
     * @return bool
     */
    private function validPosition(Model $game, string $vector): bool
    {
        $validPosition = is_null($game->board[$vector]);
        if (!$validPosition) {
            throw new HttpException(409, 'Position not empty');
        }
        return $validPosition;
    }

    /**
     * @param string $uuid
     */
    public function checkForVictory(string $uuid): void
    {
        $game = $this->accessor->get($uuid);
        $board = $game->board;
        $victory = false;

        if (!is_null($board['0,0']) && $board['0,0'] === $board['0,1'] && $board['0,1'] == $board['0,2']) {
            $victory = true;
        } else if (!is_null($board['1,0']) && $board['1,0'] === $board['1,1'] && $board['1,1'] == $board['1,2']) {
            $victory = true;
        } else if (!is_null($board['2,0']) && $board['2,0'] === $board['2,1'] && $board['2,1'] == $board['2,2']) {
            $victory = true;
        } else if (!is_null($board['0,0']) && $board['0,0'] === $board['1,0'] && $board['1,0'] == $board['2,0']) {
            $victory = true;
        } else if (!is_null($board['0,1']) && $board['0,1'] === $board['1,1'] && $board['1,1'] == $board['2,1']) {
            $victory = true;
        } else if (!is_null($board['0,2']) && $board['0,2'] === $board['1,2'] && $board['1,2'] == $board['2,2']) {
            $victory = true;
        } else if (!is_null($board['0,0']) && $board['0,0'] === $board['1,1'] && $board['1,1'] == $board['2,2']) {
            $victory = true;
        } else if (!is_null($board['2,0']) && $board['2,0'] === $board['1,1'] && $board['1,1'] == $board['0,2']) {
            $victory = true;
        }

        if ($victory) {
            $this->accessor->updateVictory($game, $game->current_turn === 'x' ? 'o' : 'x');
        }
    }

    /**
     * @param string $uuid
     */
    public function restart(string $uuid)
    {
        $this->accessor->restart($uuid);
    }

    /**
     * @param string $uuid
     */
    public function delete(string $uuid)
    {
        $this->accessor->delete($uuid);
    }
}
