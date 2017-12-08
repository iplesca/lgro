<?php
namespace Isteam\Wargaming;

/**
 * This file is part of the isteam project.
 *
 * Date: 06/12/17 22:04
 * @author ionut
 */

use Isteam\Wargaming\Platforms\Tanks;

interface ApiDefinition
{
    const REALM_EU   = 'eu';
    const REALM_RU   = 'ru';
    const REALM_NA   = 'na';
    const REALM_KR   = 'kr';
    const REALM_ASIA = 'asia';

    const PLATFORM_WOTANKS         = 'wot';
    const PLATFORM_WOTANKS_BLITZ   = 'wotb';
    const PLATFORM_WOTANKS_CONSOLE = 'wotx';
    const PLATFORM_WOWARSHIPS      = 'wow';
    const PLATFORM_WOWARPLANES     = 'wowp';
    const PLATFORM_WARGAMINGNET    = 'wgn';

    const WG_REALMS = [
        self::REALM_EU   => 'https://api.worldoftanks.eu',
        self::REALM_RU   => 'https://api.worldoftanks.ru',
        self::REALM_NA   => 'https://api.worldoftanks.na',
        self::REALM_KR   => 'https://api.worldoftanks.kr',
        self::REALM_ASIA => 'https://api.worldoftanks.asia',
    ];

    const WG_PLATFORMS = [
        self::PLATFORM_WOTANKS,
        self::PLATFORM_WOTANKS_BLITZ,
        self::PLATFORM_WOTANKS_CONSOLE,
        self::PLATFORM_WOWARSHIPS,
        self::PLATFORM_WOWARPLANES,
        self::PLATFORM_WARGAMINGNET,
    ];
    const WG_CLASSES = [
        self::PLATFORM_WOTANKS         => Tanks::class,
        self::PLATFORM_WOTANKS_BLITZ   => Tanks::class,
        self::PLATFORM_WOTANKS_CONSOLE => Tanks::class,
        self::PLATFORM_WOWARSHIPS      => Tanks::class,
        self::PLATFORM_WOWARPLANES     => Tanks::class,
        self::PLATFORM_WARGAMINGNET    => Server::class,
    ];
}