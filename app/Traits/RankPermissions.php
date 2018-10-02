<?php
namespace App\Traits;

use App\Models\User;

/**
 * This file is part of the isteam project.
 *
 * Date: 15/09/18 12:14
 * @author ionut
 */

trait RankPermissions
{
    private $LUCAS_ID = 519931899;

    private $ranks2roles = [
        "intelligence_officer" =>
            ['member', 'officer'],
        "personnel_officer" =>
            ['member', 'officer', 'personnel'],
        "quartermaster" =>
            ['member', 'officer'],
        "executive_officer" =>
            ['member', 'officer', 'executive'],
        "recruit" =>
            ['member'],
        "private" =>
            ['member'],
        "commander" =>
            ['member', 'officer', 'admin'],
        "reservist" =>
            ['member'],
        "combat_officer" =>
            ['member', 'officer', 'combat'],
        "junior_officer" =>
            ['member', 'officer'],
        "recruitment_officer" =>
            ['member', 'officer', 'recruiter'],
    ];
    private $noPermission = [];

    protected function getPermissions(User $user, $rank)
    {
        // self explanatory
        if ($this->LUCAS_ID == $user->wargaming_id) {
            return ['officer', 'superadmin'];
        }

        return isset($this->ranks2roles[$rank]) ? $this->ranks2roles[$rank] : $this->noPermission;
    }
}
