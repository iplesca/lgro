<?php
namespace Isteam\Wargaming\Platforms;

/**
 * This file is part of the isteam project.
 *
 * Date: 09/12/17 10:09
 * @author ionut
 */
use Isteam\Wargaming\Endpoint;
use Isteam\Wargaming\Exceptions\Exception as Exception;

class Server extends Base
{
    /**
     * Get the full clan info
     *
     * @param integer $clanId Wargaming clan id
     * @param string $token
     * @param array $extra
     * @return mixed
     */
    public function getClanInfo($clanId, $token = null, $extra = [])
    {
        $params = [
            'clan_id' => $this->flatten($clanId)
        ];

        if (!is_null($token)) {
            $params['access_token'] = $token;
        }

        if (!empty($extra)) {
            $params['extra'] = $this->flatten($extra);
        }

        $result = $this->execute('get', 'clans/info', $params);
        return $result[$clanId];
    }

    /**
     * Get all the members of a clan
     *
     * @param integer $clanId Wargaming clan id
     * @param string $token
     * @return array
     */
    public function getClanMembers($clanId, $token = null)
    {
        $result = [];
        $response = $this->getClanInfo($clanId, $token, ['private.online_members']);

        $online = [];
        if (!is_null($token)) {
            $online = $response['private']['online_members'];
        }

        foreach ($response['members'] as $m) {
            $result[$m['account_id']] = $m;
            $result[$m['account_id']]['online'] = in_array($m['account_id'], $online) ? true : false;
        }

        return $result;
    }
}