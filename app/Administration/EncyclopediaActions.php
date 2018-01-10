<?php
namespace App\Administration;

/**
 * This file is part of the isteam project.
 *
 * Date: 07/01/18 23:06
 * @author ionut
 */
use Illuminate\Support\Facades\Log;
use App\Clan;
use App\Member;
use Isteam\Wargaming\Api;

class EncyclopediaActions
{
    /**
     * Updates tank data changes
     * @param Api $api
     */
    public function updateTanks(Api $api)
    {
        Log::info('[cron][update tank data] running');

        $tanks = $api->tanks()->getAllTanks();
    }
}