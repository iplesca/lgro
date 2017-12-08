<?php
namespace Isteam\Wargaming\Platforms;

/**
 * This file is part of the isteam project.
 *
 * Date: 06/12/17 22:56
 * @author ionut
 */
use Isteam\Wargaming\Endpoint;
use Isteam\Wargaming\Exceptions\Exception as Exception;

class Tanks extends Api
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
        /**
         * Validate data
         */
        // ...
        
        return new Endpoint('get', 'account/list', [
            'search' => $search,
            'type'   => $type,
            'limit'  => $limit,
        ]);
    }
    public function getUserData($id, $accessToken)
    {
        /**
         * Validate data
         */
        // ...

        return new Endpoint('get', 'account/info', [
            'account_id' => $id,
            'access_token' => $accessToken,
        ]);
    }
}