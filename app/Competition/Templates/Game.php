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

class Game implements Component
{
    /**
     * Game scoring types to use
     * @var array
     */
    private $score = [];
    /**
     * Game scoring computation rules
     * @var array
     */
    private $scoreCompute = [C::WINNER_SCORE];
    /**
     * Defines if a game can end in a draw
     * @var bool
     */
    private $canDraw;
    /**
     * Points for a game draw (can be negative also)
     * @var integer
     */
    private $drawPoints = -1;

    /**
     * Use "winner" scoring.
     * Set to integer to define points awarded.
     * Set to FALSE to remove scoring method.
     * @param int|bool $points Defaults to self::WINNER_POINTS if not set
     */
    public function useWinnerScore($points = C::WINNER_POINTS)
    {
        if (false === $points) {
            unset($this->score[C::WINNER_SCORE]);
        } else {
            if (empty($points)) {
                $points = C::WINNER_POINTS;
            }
            $this->score[C::WINNER_SCORE] = $points;
        }
    }
    /**
     * Use "kills" scoring.
     * Set to integer to define points awarded.
     * Set to FALSE to remove method
     * @param int|bool $points Defaults to self::KILLS_POINTS if not set
     */
    public function useKillsScore($points = C::KILLS_POINTS)
    {
        if (false === $points) {
            unset($this->score[C::KILLS_SCORE]);
        } else {
            if (empty($points)) {
                $points = C::KILLS_POINTS;
            }
            $this->score[C::KILLS_SCORE] = $points;
        }
    }
    /**
     * Use "capture" scoring.
     * Set to integer to define points awarded.
     * Set to FALSE to remove scoring method.
     * @param int|bool $points Defaults to self::CAPTURE_POINTS if not set
     */
    public function useCaptureScore($points = C::CAPTURE_POINTS)
    {
        if (false === $points) {
            unset($this->score[C::CAPTURE_SCORE]);
        } else {
            if (empty($points)) {
                $points = C::CAPTURE_POINTS;
            }
            $this->score[C::CAPTURE_SCORE] = $points;
        }
    }
    /**
     * Define which scoring types to sum up to calculate the total points
     * @param array $mix1
     */
    public function setScoreCompute(array $mix1 = [C::WINNER_SCORE])
    {
        $this->scoreCompute = $mix1;
    }

    /**
     * Define if a game can accept draw
     * @param bool $status
     */
    public function canDraw($status, $drawPoints = C::DRAW_POINTS)
    {
        $this->canDraw = $status;
        $this->drawPoints = $drawPoints;
    }
    public function export()
    {
        $result = [
            'score' => $this->score,
            'compute' => $this->scoreCompute,
            'draw' => $this->drawPoints
        ];
        return $result;
    }
}