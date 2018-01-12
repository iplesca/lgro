<?php
namespace Isteam\Wargaming\Platforms;

/**
 * This file is part of the isteam project.
 *
 * Date: 06/12/17 22:56
 * @author ionut
 */
use Isteam\Wargaming\Exceptions\Exception as Exception;

class Tanks extends Base
{
    /**
     * $params = [
     * 'search' => Player name search string. Parameter 'type' defines minimum length and type of search.
     *             Maximum length: 24 symbols
     *  'limit' => Number of returned entries (fewer can be returned, but not more than 100).
     *            If the limit sent exceeds 100, an limit of 100 is applied (by default)
     *  'type' => Search type. Defines minimum length and type of search.
     *            Default value: startswith. Default is startswith.
     *            Valid values:
     *              "startswith" — Search by initial characters of player name.
     *                             Minimum length: 3 characters. Case insensitive. (by default)
     *              "exact" — Search by exact match of player name.
     *                        Minimum length: 1 character. Case insensitive.
     * ]
     */
    public function searchPlayerByNamePattern($search, $type = 'startswith', $limit = 100)
    {
        $result = $this->execute('get', 'account/list', [
            'search' => $search,
            'type'   => $type,
            'limit'  => $limit,
        ]);
        
        return $result;
    }

    /**
     * Get Wargaming account data for WoT.
     * Returns a single entry or array depending if $id is a integer or an array of ids.
     *
     * @param integer $id Wargaming account id
     * @param string $accessToken Wargaming access token
     * @return array
     */
    public function getUserData($id, $accessToken = '')
    {
        $result = $this->execute('get', 'account/info', [
            'account_id' => $this->flatten($id),
            'access_token' => $accessToken,
        ]);
        
        if (! is_array($id) && is_numeric($id)) { 
            $result = $result[$id];
        }

        return $result;
    }

    /**
     * Get all WG game tanks
     * @return array
     */
    public function getAllTanks()
    {
        $result = $this->execute('get', 'encyclopedia/tanks');
        return $result;
    }

    /**
     * Get player tanks
     * @param integer $id
     * @param string $accessToken
     * @return array
     */
    public function getPlayerTanks($id, $accessToken = '')
    {
        $result = $this->execute('get', 'account/tanks', [
            'account_id' => $this->flatten($id),
            'access_token' => $accessToken,
        ]);
        if (! is_array($id) && is_numeric($id)) {
            $result = $result[$id];
        }

        return $result;
    }

    /**
     * Get the stats of a player's tank
     * @param integer $id
     * @param string $accessToken
     * @param integer $tankId
     * @return array
     */
    public function getPlayerTankStats($id, $accessToken = '', $tankId = 0)
    {
        $params = [
            'account_id' => $this->flatten($id),
            'access_token' => $accessToken,
        ];
        if (!empty($tankId)) {
            $params['tank_id'] = $tankId;
        }
        $result = $this->execute('get', 'tanks/stats', $params);

        if (! is_array($id) && is_numeric($id)) {
            $result = $result[$id];
        }

        return $result;
    }
    public function getPlayerTankAchievements($id, $accessToken = '', $tankId = false)
    {
        $params = [
            'account_id' => $this->flatten($id),
            'access_token' => $accessToken,
        ];
        if (!empty($tankId)) {
            $params['tank_id'] = $tankId;
        }
        $result = $this->execute('get', 'tanks/achievements', $params);

        return $result;
    }
}