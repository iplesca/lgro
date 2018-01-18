<?php
namespace App\Administration;

/**
 * This file is part of the isteam project.
 *
 * Date: 07/01/18 23:06
 * @author ionut
 */
use App\Models\Tank;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
    public function updateWn8Base($config)
    {
        Log::info('[cron][update WN8 base] running');

        $content = json_decode(file_get_contents($config['wn8BaseSource']), true);

        if (is_null($content)) {
            Log::error('[cron][update WN8 base] Cannot read/decode remote file');
        } else {
            $baseTanks = [];
            foreach ($content['data'] as $t) {
                $baseTanks[$t['IDNum']] = [
                    'damage' => $t['expDamage'],
                    'spot'   => $t['expSpot'],
                    'frag'   => $t['expFrag'],
                    'def'    => $t['expDef'],
                    'win'    => $t['expWinRate'],
                ];
            }
            Storage::put($config['wn8Base'], '<?php return ' . var_export([
                'data' => $baseTanks,
                'version' => $content['header']['version']
            ], true) . '; ');
            Log::info('[cron][update WN8 base] finished');
        }
    }
}