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
































