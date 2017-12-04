<?php
namespace WgApi;

use WgApi\WgApi as Base;
use WgApi\WgClient;
use WgApi\Interfaces\WgApiDefinition;

class WotApi extends Base
{
    protected $platform = WgApiDefinition::PLATFORM_WOTANKS;
    protected $accountIds = [];
    protected $definedEndpoints = [
        'account/list' => [
            'verb'     => 'get',
            'required' => ['search'],
            'params'   => ['search', 'limit', 'type', 'language'],
        ],
        'account/info' => [
            'verb'     => 'get',
            'required' => ['account_id'],
            'params'   => ['account_id', 'access_token', 'extra', 'fields', 'language'],
        ]
    ];
    /**
     * $params = [
     *      'search' => Player name search string. Parameter 'type' defines minimum length and type of search. 
     *                  Maximum length: 24 symbols
     *      'limit' => Number of returned entries (fewer can be returned, but not more than 100). 
     *                 If the limit sent exceeds 100, an limit of 100 is applied (by default)
     *      'type' => Search type. Defines minimum length and type of search. Default value: startswith. Default is startswith. 
     *                Valid values:
     *                "startswith" — Search by initial characters of player name. 
     *                               Minimum length: 3 characters. Case insensitive. (by default)
     *                "exact" — Search by exact match of player name. 
     *                          Minimum length: 1 character. Case insensitive.
     * ]
     * 
     * @param array $params
     * @return mixed
     */
    public function searchPlayerByNamePattern($search, $type = 'startswith', $limit = 100)
    {
        $result = false;
        
        if ($this->createEndpoint('account/list')) {
            $this->setParams([
                'search' => $search,
                'type'   => $type,
                'limit'  => $limit,
            ]);
            $result = $this->execute();
        }
        
        return $result;
    }
    public function getUserData($id, $accessToken)
    {
        $result = false;

        if ($this->createEndpoint('account/info')) {
            $this->setParams([
                'account_id' => $id,
                'access_token' => $accessToken,
            ]);
            $result = $this->execute();
            $result = $result[$id];
        }

        return $result;
    }
}