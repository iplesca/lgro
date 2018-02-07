<?php
namespace Isteam\Wargaming\Platforms;

/**
 * This file is part of the isteam project.
 *
 * Date: 06/12/17 22:56
 * @author ionut
 */
use Illuminate\Support\Facades\Log;
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
     * NOTE on $accessToken:
     *  - if set to false then it won't be included in the WG call
     *  - if not set (default to empty string), the current authorized user token will be used
     *
     * @param mixed $wargamingAccountId Wargaming account id
     * @param string $accessToken [default=''] Wargaming access token
     * @return array
     */
    public function getPlayerData($wargamingAccountId, $accessToken = '')
    {
        $params = [
            'account_id' => $this->flatten($wargamingAccountId),
        ];

        if (false === $accessToken || !empty($accessToken)) {
            $params['access_token'] = $accessToken;
        }

        $result = $this->execute('get', 'account/info', $params);
        
        if (! is_array($wargamingAccountId) && is_numeric($wargamingAccountId)) {
            $result = $result[$wargamingAccountId];
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
    public function getPlayerTanks($wargamingAccountId, $accessToken = '')
    {
        $result = $this->execute('get', 'account/tanks', [
            'account_id' => $this->flatten($wargamingAccountId),
            'access_token' => $accessToken,
        ]);
        if (! is_array($wargamingAccountId) && is_numeric($wargamingAccountId)) {
            $result = $result[$wargamingAccountId];
        }

        return $result;
    }

    /**
     * Get the stats of a player's tank
     * @param integer $wargamingAccountId
     * @param string $accessToken
     * @param mixed $tankIds
     * @param mixed $extra
     * @return array
     */
    public function getPlayerTankStats(
        $wargamingAccountId,
        $accessToken = '',
        $tankIds = [],
        $extra = [],
        $extraParams = []
    ) {
        $params = [
            'account_id' => $wargamingAccountId,
            'access_token' => $accessToken,
        ];
        if (!empty($tankIds)) {
            $params['tank_id'] = $this->flatten($tankIds);
        }
        if (!empty($extraParams)) {
            $params = array_merge($extraParams, $params);
        }
        if (!empty($extra)) {
            $params['extra'] = $this->flatten($extra);
        }
        $result = $this->execute('get', 'tanks/stats', $params);

        return $result[$wargamingAccountId];
    }
    public function getPlayerTankAchievements($wargamingAccountId, $accessToken = '', $tankId = false)
    {
        $params = [
            'account_id' => $this->flatten($wargamingAccountId),
            'access_token' => $accessToken,
        ];
        if (!empty($tankId)) {
            $params['tank_id'] = $tankId;
        }
        $result = $this->execute('get', 'tanks/achievements', $params);

        return $result;
    }
    public function getPlayerNewToken($accessToken = '')
    {
        $params = [
            'access_token' => $accessToken,
        ];
        // special handling, token might not work (too new)
        try {
            $result = $this->execute('get', 'auth/prolongate', $params);
        } catch (\Exception $e) {
            $result = false;
        }

        return $result;
    }
}
