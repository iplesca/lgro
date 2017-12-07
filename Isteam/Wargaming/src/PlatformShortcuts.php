<?php
namespace Isteam\WargamingApi;
/**
 * This file is part of the isteam project.
 *
 * Date: 06/12/17 21:44
 * @author ionut
 */
use Isteam\WargamingApi\ApiDefinition as Definition;

trait PlatformShortcuts
{
    protected $usePlatform;

    public function tanks()
    {
        $this->usePlatform = Definition::PLATFORM_WOTANKS;
        return $this;
    }
    public function server()
    {
        $this->usePlatform = Definition::PLATFORM_WARGAMINGNET;
        return $this;
    }
    public function tanksBlitz()
    {
        $this->usePlatform = Definition::PLATFORM_WOTANKS_BLITZ;
        return $this;
    }
    public function tanksConsole()
    {
        $this->usePlatform = Definition::PLATFORM_WOTANKS_CONSOLE;
        return $this;
    }
    public function planes()
    {
        $this->usePlatform = Definition::PLATFORM_WOWARPLANES;
        return $this;
    }
    public function ships()
    {
        $this->usePlatform = Definition::PLATFORM_WOWARSHIPS;
        return $this;
    }
}