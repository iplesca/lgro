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

class Stage implements Component
{
    private $stageType;
    /**
     * Type of mechanics (BRACKET, ROUND_ROBIN)
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
     * @var array Group elements
     */
    private $groups = [];
    /**
     * Tie-match definition
     * @var Match
     */
    private $tieMatch = null;

    public function __construct($type)
    {
        $this->stageType = $type;
    }

    /**
     * Group definition
     * @param integer $nr
     * @return Group
     */
    public function groups($nr = 1)
    {
        if (is_null($this->groups[$nr])) {
            $this->groups[$nr] = new Group();
        }
        return $this->groups[$nr];
    }
    /**
     * Play-off definition
     * @return Match
     */
    public function tieMatch()
    {
        if (is_null($this->tieMatch)) {
            $this->tieMatch = new Match();
        }
        return $this->tieMatch;
    }
    public function hasTieMatch()
    {
        return is_null($this->tieMatch) ? false : true;
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
        $this->matchSystem = C::BRACKET;
    }
    /**
     * Set the qualification phase to use the Round-Robin system
     */
    public function useRoundRobinType()
    {
        $this->matchSystem = C::ROUND_ROBIN;
    }

    /**
     * Set number of promoted teams per group
     * @param integer $nrPromoted Number of teams promoted in groups
     */
    public function setPromotedNumber($nrPromoted)
    {
        $this->nrTeamsPromoted = $nrPromoted;
    }
    public function export()
    {
        $result = [
            'type' => $this->stageType,
            'matchSystem' => $this->matchSystem,
            'nrGroups' => $this->nrGroups,
            'nrPromoted' => $this->nrTeamsPromoted,
        ];
        if ($this->hasTieMatch()) {
            $result['tie'] = $this->tieMatch()->export();
        }
        if ($this->nrGroups) {
            foreach ($this->groups as $group) {
                $result['groups'][] = $group->export();
            }
        }
        return $result;
    }
}