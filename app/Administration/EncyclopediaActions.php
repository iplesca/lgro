<?php
namespace App\Administration;

/**
 * This file is part of the isteam project.
 *
 * Date: 07/01/18 23:06
 * @author ionut
 */
use App\Tank;
use Illuminate\Support\Facades\Log;
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
        foreach ($tanks as $tank) {
            $data = [
                'wargaming_id' => $tank['tank_id'],
                'nation' => $tank['nation'],
                'tier' => $tank['level'],
                'type' => Tank::getType($tank['type']),
                'name' => $tank['name_i18n'],
                'name_short' => $tank['short_name_i18n'],
                'name_uri' => $tank['name'],
                'premium' => empty($tank['is_premium']) ? 'no' : 'yes',
                'image' => $tank['image'],
                'image_small' => $tank['image_small'],
                'image_contour' => $tank['contour_image'],
            ];
            $dbTank = Tank::updateOrCreate($data);
            $dbTank->wargaming_id = $tank['tank_id'];
            $dbTank->save();
        }
        Log::info('[cron][update tank data] Looped: ' . count($tanks));
    }
}