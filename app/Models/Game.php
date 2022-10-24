<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Game extends Model implements \JsonSerializable
{
    use HasFactory, Uuids;

    protected $fillable = [
        'victory',
        'current_turn',
        'score_x',
        'score_o',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public function board(): hasOne
    {
        return $this->hasOne(GameBoard::class);
    }
}
