<?php 
namespace Server\Models;

class Game
{
    public $gameId;
    public $gameMode;
    public $gameType;
    public $gameStartTime;
    public $teams;
    public $platformId;

    public function __construct($gameId, $gameMode, $gameType, $gameStartTime, $teams, $platformId)
    {
        $this->gameId = $gameId;
        $this->gameMode = $gameMode;
        $this->gameStartTime = $gameStartTime;
        $this->gameType = $gameType;
        $this->teams = $teams;
        $this->platformId = $platformId;
    }
}