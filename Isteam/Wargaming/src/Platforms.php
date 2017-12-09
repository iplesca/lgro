<?php
namespace Isteam\Wargaming;
/**
 * This file is part of the isteam project.
 *
 * Date: 06/12/17 21:44
 * @author ionut
 */
use Isteam\Wargaming\ApiDefinition as Definition;

trait Platforms
{
    public function tanks()
    {
        $this->use = Definition::PLATFORM_WOTANKS;
        return $this;
    }
    public function server()
    {
        $this->use = Definition::PLATFORM_WARGAMINGNET;
        return $this;
    }
    public function tanksBlitz()
    {
        $this->use = Definition::PLATFORM_WOTANKS_BLITZ;
        return $this;
    }
    public function tanksConsole()
    {
        $this->use = Definition::PLATFORM_WOTANKS_CONSOLE;
        return $this;
    }
    public function planes()
    {
        $this->use = Definition::PLATFORM_WOWARPLANES;
        return $this;
    }
    public function ships()
    {
        $this->use = Definition::PLATFORM_WOWARSHIPS;
        return $this;
    }
}