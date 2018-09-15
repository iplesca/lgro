<?php
namespace App\Competition\Interfaces;

/**
 * This file is part of the isteam project.
 *
 * Date: 29/12/17 10:16
 * @author ionut
 */

interface Configuration
{
    const QUALIFICATION_STAGE = 'qualification';
    const ELIMINATION_STAGE = 'knockout';
    /** Game systems */
    /**
     * Bracket type system
     */
    const BRACKET = 'bracket';
    /**
     * Round-Robin type system
     */
    const ROUND_ROBIN = 'roundrobin';
    /** Match types */
    /**
     * Single match type (minGames=maxGames= 1)
     */
    const SINGLE = 'single';
    /**
     * Home-away match type (minGames=2, maxGames=2)
     */
    const HOME_AWAY = 'homeaway';
    /**
     * Best-of-X match type (minGames= maxGames/2 +1)
     */
    const BEST_OF = 'bestof';
    /**
     * Number of BEST_OF games to play
     */
    const BEST_OF_GAMES = 3;
    /** Game scoring types */
    /**
     * Collect game winner/loser result
     */
    const WINNER_SCORE = 'winner';
    /**
     * Collect team kills result
     */
    const KILLS_SCORE = 'kills';
    /**
     * Collect game capture result
     */
    const CAPTURE_SCORE = 'capture';
    /**
     * Collect game capture result
     */
    const DRAW_SCORE = 'draw';
    /** Default game scoring points */
    /**
     * Points awarded for the winning team
     */
    const WINNER_POINTS = 3;
    /**
     * Points awarded for the losing team
     */
    const LOSER_POINTS = 0;
    /**
     * Points awarded for each kill per team
     */
    const KILLS_POINTS = 1;
    /**
     * Points awarded for capturing the loser's base
     */
    const CAPTURE_POINTS = 5;
    /**
     * Points awarded in the event of a game draw
     */
    const DRAW_POINTS = 1;
}