<?php
namespace App\Competition\Templates;

/**
 * This file is part of the isteam project.
 *
 * Date: 04/01/18 07:32
 * @author ionut
 */

use App\Competition\Interfaces\Configuration as C;
use App\Competition\Interfaces\Component;

class Match implements Component
{
    /**
     * Match type (SINGLE, HOME_AWAY, BEST_OF)
     * @var string
     */
    private $type;
    /**
     * Game configuration
     * @var Game
     */
    private $game = null;
    /**
     * Minimum games to be played
     * @var integer
     */
    private $minGames;
    /**
     * Maximum games to be played
     * @var integer
     */
    private $maxGames;

    /**
     * Game definition
     * @return Game
     */
    public function game()
    {
        if (is_null($this->game)) {
            $this->game = new Game();
        }
        return $this->game;
    }
    /**
     * Set the match type to use Single game
     */
    public function useSingleType()
    {
        $this->type = C::SINGLE;
        $this->minGames = 1;
        $this->maxGames = 1;
    }
    /**
     * Set the match type to use Home-Away games
     */
    public function useHomeAwayType()
    {
        $this->type = C::HOME_AWAY;
        $this->minGames = 2;
        $this->maxGames = 2;
    }
    /**
     * Set the match type to use Best of X games
     * @param integer $maxGames Maximum games
     */
    public function useBestOfType($maxGames)
    {
        $this->type = C::BEST_OF;
        $this->minGames = floor($maxGames/2) + 1;
        $this->maxGames = $maxGames;
    }
    public function export()
    {
        $result = [
            'type' => $this->type,
            'minGames' => $this->minGames,
            'maxGames' => $this->maxGames,
            'game' => $this->game()->export()
        ];

        return $result;
    }
}