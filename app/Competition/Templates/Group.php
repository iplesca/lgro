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

class Group implements Component
{
    /**
     * Match definition
     * @var Match
     */
    private $match = null;
    private $teams = [];

    /**
     * Match definition
     * @return Match
     */
    public function match()
    {
        if (is_null($this->match)) {
            $this->match = new Match();
        }
        return $this->match;
    }
    public function export()
    {
        $result = [
            'match' => $this->match()->export()
        ];
        return $result;
    }
}