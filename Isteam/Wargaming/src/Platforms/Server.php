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
     * @return mixed
     */
    public function getClanInfo($clanId)
    {
        $result = $this->execute('get', 'clans/info', [
            'clan_id' => $this->flatten($clanId)
        ]);
        return $result[$clanId];
    }

    /**
     * Get all the members of a clan
     *
     * @param integer $clanId Wargaming clan id
     * @return mixed
     */
    public function getClanMembers($clanId, $idAsKey = true)
    {
        $result = [];
        $response = $this->getClanInfo($clanId);

        if ($idAsKey) {
            foreach ($response['members'] as $m) {
                $result[$m['account_id']] = $m;
            }
        } else {
            $result = $response['members'];
        }

        return $result;
    }
}