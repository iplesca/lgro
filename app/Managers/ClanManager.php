<?php

namespace App\Managers;
use App\Models\Clan;

/**
 * This file is part of the isteam project.
 *
 * Date: 04/10/18 08:15
 * @author ionut
 */

class ClanManager
{
    /**
     * @var Clan
     */
    static protected $clan = null;
    static protected $clanTag = '';
    static protected $clanId = -1;

    static public function identifyTag($clanReg)
    {
        if (self::loadDataByTag($clanReg)) {
            return true;
        }

        return false;
    }
    static public function loadDataByTag($clanTag)
    {
        $result = Clan::getByWargamingTag($clanTag);
        if ($result) {
            self::$clan = $result;
            self::$clanId = $result->wargaming_id;
            self::$clanTag = $clanTag;
            return true;
        }

        return false;
    }
    static public function loadDataById($clanId)
    {
        $result = Clan::getByWargamingId($clanId);
        if ($result) {
            self::$clan = $result;
            self::$clanId = $clanId;
            self::$clanTag = $result->tag;
            return true;
        }

        return false;
    }
    static public function getSubdomain($secure, $trail = false)
    {
        $result = false;
        $trail = $trail ? '/' : '';

        $proto = 'http' . ($secure ? 's' : '');
        if (!empty(self::$clan->subdomain)) {
            $result = $proto . '://' . self::$clan->subdomain . '.isteam.dev' . $trail;
        }

        return $result;
    }
    static public function isClan()
    {
        return ! is_null(self::$clan);
    }
    static public function getClanId()
    {
        return self::$clanId;
    }
    static public function getClanTag()
    {
        return self::$clanTag;
    }
    static public function getClan()
    {
        return self::$clan;
    }
}
