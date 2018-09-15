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

class Competition implements Component
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
        $this->name = $name;
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
            $this->standard = new Stage(C::ELIMINATION_STAGE);
        }
        return $this->standard;
    }
    /**
     * Stage qualification object
     */
    public function qualification()
    {
        if (is_null($this->qualification)) {
            $this->qualification = new Stage(C::QUALIFICATION_STAGE);
        }
        return $this->qualification;
    }

    /**
     * Validate
     */
    public function validate()
    {
    }
    public function export()
    {
        $result = [
            'name' => $this->name,
            'nrTeams' => $this->nrTeams,
            'standard' => $this->standard()->export()
        ];
        if ($this->hasQualifications()) {
            $result['qualification'] = $this->qualification()->export();
        }
        return $result;
    }
}