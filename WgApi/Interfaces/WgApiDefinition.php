<?php
namespace WgApi\Interfaces;

interface WgApiDefinition
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
    const WG_APIS = [
        self::PLATFORM_WOTANKS => "Isteam\\Wargaming\\Platforms\\Tanks",
        self::PLATFORM_WOTANKS_BLITZ => "Isteam\\Wargaming\\Platforms\\TanksBlitz",
        self::PLATFORM_WOTANKS_CONSOLE => "Isteam\\Wargaming\\Platforms\\TanksConsole",
        self::PLATFORM_WOWARSHIPS => "Isteam\\Wargaming\\Platforms\\Ships",
        self::PLATFORM_WOWARPLANES => "Isteam\\Wargaming\\Platforms\\Planes",
        self::PLATFORM_WARGAMINGNET => "Isteam\\Wargaming\\Platforms\\Server",
    ];
}