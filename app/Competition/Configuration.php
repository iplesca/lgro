<?php
namespace App\Competition;
/**
 * This file is part of the isteam project.
 *
 * Date: 29/12/17 10:14
 * @author ionut
 */

use App\Competition\Interfaces\Part as CompetitionPhase;
use Illuminate\Database\Eloquent\Model;

class Configuration
{
    private $teamNames = ['Alfa', 'Bravo', 'Charlie', 'Delta', 'Echo', 'Foxtrot', 'Golf', 'Hotel', 'India', 'Juliet', 'Kilo', 'Lima', 'Mike', 'November', 'Oscar', 'Papa', 'Quebec', 'Romeo', 'Sierra', 'Tango', 'Uniform', 'Victor', 'Whiskey', 'X-ray', 'Yankee', 'Zulu'];

    private $nrTeams = 0;
    private $parts = [];

    public function setNames(array $names)
    {
        $this->teamNames = $names;
    }
    public function setTeamNumber($nrTeams)
    {
        $this->nrTeams = $nrTeams;
    }
    public function addPart($shortName, CompetitionPhase $part)
    {
        $this->parts[$shortName] = $part;
    }

    /**
     * Generate the entire competition structure
     */
    public function generate()
    {

    }
}
class Phase implements CompetitionPhase
{

}

//////////////////
class Game extends Model
{
    protected $table = 'competition_games';
    public $timestamps = false;

    private $status;
    private $teamOne;
    private $teamTwo;

    private $winner;
    private $loser;

    private $canDraw;
    private $scheduledTime;
    private $startTime;
    private $endTime;
    private $replayedId;

    private $stats = [];

    public function match()
    {
        return $this->belongsTo('App\Competition\MatchData');
    }
    public function setTeamOne() {}
    public function setTeamTwo() {}
    public function getWinner() {}
    public function getLoser() {}
    public function isDraw() {}

    public function getStatus() {}
    public function isFinished() {}
    public function isStarted() {}
    public function isReplayed() {}
    public function getReplayed() {}
}
class MatchData extends Model
{
    private $table = 'competition_matches';
    public $timestamps = false;

    public function games()
    {
        return $this->hasMany('App\Competition\Game', 'match_id');
    }

}
class Match
{
    private $teamOne;
    private $teamTwo;
    private $games = [];

    /**
     * How many games have been played
     * @var int
     */
    private $playedGames = 0;
    /**
     * home-away: minGames = maxGames
     * best-of: minGames = maxGames/2 + 1
     * @var int
     */
    private $minGames;
    /**
     * Maximum games to be played
     * @var int
     */
    private $maxGames;
    /**
     * home-away, best-of
     */
    private $finishConditions;
    /**
     * home-away: playedGames == maxGames
     * best-of: playedGames == minGames && minGames == one team wins
     */
    private $endConditions;

    public function __construct($minGames, $maxGames)
    {
        $this->minGames = $minGames;
        $this->maxGames = $maxGames;
    }

    public function getPlayed()
    {
        return $this->playedGames;
    }
    public function getMax()
    {
        return $this->maxGames;
    }
    public function getMin()
    {
        return $this->minGames;
    }
    public function getPlayedGames()
    {

    }
    /**
     * Is the match finished
     * @return bool
     */
    public function isFinished()
    {
        return $this->finishConditions->execute($this);
    }
    public function setFinishConditions(MatchFinish $finisher)
    {
        $this->finishConditions = $finisher;
    }
    public function getWinner() {}
    public function getLoser() {}

    public function generateGames() {}
}
interface MatchFinish
{
    /**
     * @param Match $match
     * @return bool
     */
    public function execute(Match $match);
}
class BestOfX implements MatchFinish
{
    /**
     * @var Match
     */
    private $match;
    public function execute(Match $match)
    {
        $this->match = $match;
        return $this->isMinimumPlayed() && $this->oneTeamMinVictories();

    }
    private function isMinimumPlayed()
    {
        return ($this->match->getPlayed() == $this->match->getMin()) ? true : false;
    }
    private function oneTeamMinVictories()
    {

    }
}
class Board
{
    private $nrPromoted;
    private $nrTeams;
    private $team = [];
    private $matches = [];

    public function getPromotedSlots() {}
    public function setMatches(array $matches) {}
    public function setTeams(array $teams) {}
}

class Competition
{

}
interface CompetitionTemplateSettings
{
    const QUALIFICATION_STAGE = 1;
    const ELIMINATION_STAGE = 2;
    /** Game systems */
    /**
     * Bracket type system
     */
    const BRACKET = 1;
    /**
     * Round-Robin type system
     */
    const ROUND_ROBIN = 2;
    /** Match types */
    /**
     * Single match type (minGames=maxGames= 1)
     */
    const SINGLE = 1;
    /**
     * Home-away match type (minGames=2, maxGames=2)
     */
    const HOME_AWAY = 2;
    /**
     * Best-of-X match type (minGames= maxGames/2 +1)
     */
    const BEST_OF = 3;
    /**
     * Number of BEST_OF games to play
     */
    const BEST_OF_GAMES = 3;
    /** Game scoring types */
    /**
     * Collect game winner/loser result
     */
    const WINNER_SCORE = 1;
    /**
     * Collect team kills result
     */
    const KILLS_SCORE = 2;
    /**
     * Collect game capture result
     */
    const CAPTURE_SCORE = 3;
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
class CompetitionTemplate implements CompetitionTemplateSettings
{
    /**
     * Competition name
     * @var string
     */
    private $name;
    /**
     * Number of teams in the competition
     * @var integer
     */
    private $nrTeams;

    private $standard = null;
    private $qualification = null;

    public function __construct($name)
    {
        $this->name;
    }

    /** General settings */
    /**
     * Set teams number
     * @param int $nr Number of teams in the competition
     */
    public function setTeamNumber($nr = 2)
    {
        $this->nrTeams = $nr;
    }
    /**
     * The competition has a qualification phase
     */
    public function hasQualifications()
    {
        return is_null($this->qualification) ? false : true;
    }

    /**
     * Stage standard (elimination) object
     */
    public function standard()
    {
        if (is_null($this->standard)) {
            $this->standard = new Stage(self::ELIMINATION_STAGE);
        }
        return $this->standard;
    }
    /**
     * Stage qualification object
     */
    public function qualification()
    {
        if (is_null($this->qualification)) {
            $this->qualification = new Stage(self::QUALIFICATION_STAGE);
        }
        return $this->qualification;
    }

    /**
     * Validate
     */
    public function validate()
    {
    }
}
class Stage implements CompetitionTemplateSettings
{
    private $stageType;
    /**
     * Type of qualification phase (BRACKET, ROUND_ROBIN)
     * @var string
     */
    private $matchSystem;
    /**
     * Number of qualification groups
     * @var integer
     */
    private $nrGroups;
    /**
     * Number of teams promoted from qualification groups.
     * Must have $nrGroups > 1
     * @var integer
     */
    private $nrTeamsPromoted;
    /**
     * Match definition
     * @var array GroupConfiguration elements
     */
    private $groups = [];
    /**
     * Play-off definition
     * @var MatchConfiguration
     */
    private $playoff = null;

    public function __construct($type)
    {
        $this->stageType = $type;
    }

    /**
     * Group definition
     * @return GroupConfiguration
     */
    public function groups($nr = 1)
    {
        if (is_null($this->groups[$nr])) {
            $this->groups[$nr] = new MatchConfiguration();
        }
        return $this->groups[$nr];
    }
    /**
     * Play-off definition
     * @return MatchConfiguration
     */
    public function playoff()
    {
        if (is_null($this->playoff)) {
            $this->playoff = new MatchConfiguration();
        }
        return $this->playoff;
    }
    /**
     * Set groups number
     * @param int $nrGroups Number of groups in the qualification phase
     */
    public function setGroupsNumber($nrGroups = 1)
    {
        $this->nrGroups = $nrGroups;
    }
    /**
     * Set qualification phase to use the Bracket system
     */
    public function useBracketType()
    {
        $this->matchSystem = self::BRACKET;
    }
    /**
     * Set the qualification phase to use the Round-Robin system
     */
    public function useRoundRobinType()
    {
        $this->matchSystem = self::ROUND_ROBIN;
    }

    /**
     * Set number of promoted teams per group
     * @param integer $nrPromoted Number of teams promoted in groups
     */
    public function setPromotedNumber($nrPromoted)
    {
        $this->nrTeamsPromoted = $nrPromoted;
    }
}
class GroupConfiguration implements CompetitionTemplateSettings
{
    /**
     * Match definition
     * @var MatchConfiguration
     */
    private $match = null;
    private $teams = [];

    public function __construct($type)
    {
        $this->stageType = $type;
    }

    /**
     * Match definition
     * @return MatchConfiguration
     */
    public function match()
    {
        if (is_null($this->match)) {
            $this->match = new MatchConfiguration();
        }
        return $this->match;
    }
}
class MatchConfiguration implements CompetitionTemplateSettings
{
    /**
     * Match type (SINGLE, HOME_AWAY, BEST_OF)
     * @var string
     */
    private $type;
    /**
     * Game configuration
     * @var GameConfiguration
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
     * @return GameConfiguration
     */
    public function game()
    {
        if (is_null($this->game)) {
            $this->game = new GameConfiguration();
        }
        return $this->game;
    }
    /**
     * Set the match type to use Single game
     */
    public function useSingleType()
    {
        $this->type = self::SINGLE;
        $this->minGames = 1;
        $this->maxGames = 1;
    }
    /**
     * Set the match type to use Home-Away games
     */
    public function useHomeAwayType()
    {
        $this->type = self::HOME_AWAY;
        $this->minGames = 2;
        $this->maxGames = 2;
    }
    /**
     * Set the match type to use Best of X games
     * @param integer $maxGames Maximum games
     */
    public function useBestOfType($maxGames)
    {
        $this->type = self::BEST_OF;
        $this->minGames = floor($maxGames/2) + 1;
        $this->maxGames = $maxGames;
    }
}
class GameConfiguration implements CompetitionTemplateSettings
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
    private $scoreCompute = [self::WINNER_SCORE];
    /**
     * Defines if a game can end in a draw
     * @var bool
     */
    private $canDraw;
    /**
     * Points for a game draw (can be negative also)
     * @var integer
     */
    private $drawPoints;

    /**
     * Use "winner" scoring.
     * Set to integer to define points awarded.
     * Set to FALSE to remove scoring method.
     * @param int|bool $points Defaults to self::WINNER_POINTS if not set
     */
    public function useWinnerScore($points = self::WINNER_POINTS)
    {
        if (false === $points) {
            unset($this->score[self::WINNER_SCORE]);
        } else {
            if (empty($points)) {
                $points = self::WINNER_POINTS;
            }
            $this->score[self::WINNER_SCORE] = $points;
        }
    }
    /**
     * Use "kills" scoring.
     * Set to integer to define points awarded.
     * Set to FALSE to remove method
     * @param int|bool $points Defaults to self::KILLS_POINTS if not set
     */
    public function useKillsScore($points = self::KILLS_POINTS)
    {
        if (false === $points) {
            unset($this->score[self::KILLS_SCORE]);
        } else {
            if (empty($points)) {
                $points = self::KILLS_POINTS;
            }
            $this->score[self::KILLS_SCORE] = $points;
        }
    }
    /**
     * Use "capture" scoring.
     * Set to integer to define points awarded.
     * Set to FALSE to remove scoring method.
     * @param int|bool $points Defaults to self::CAPTURE_POINTS if not set
     */
    public function useCaptureScore($points = self::CAPTURE_POINTS)
    {
        if (false === $points) {
            unset($this->score[self::CAPTURE_SCORE]);
        } else {
            if (empty($points)) {
                $points = self::CAPTURE_POINTS;
            }
            $this->score[self::CAPTURE_SCORE] = $points;
        }
    }
    /**
     * Define which scoring types to sum up to calculate the total points
     * @param array $mix1
     */
    public function setScoreCompute(array $mix1 = [self::WINNER_SCORE])
    {
        $this->scoreCompute = $mix1;
    }

    /**
     * Define if a game can accept draw
     * @param bool $status
     */
    public function canDraw($status, $drawPoints = self::DRAW_POINTS)
    {
        $this->canDraw = $status;
        $this->drawPoints = $drawPoints;
    }
}

























