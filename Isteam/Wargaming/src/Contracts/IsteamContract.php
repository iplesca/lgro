<?php
namespace Isteam\WargamingApi\Contracts;

use Isteam\WargamingApi\Configuration;

/**
 * This file is part of the isteam project.
 *
 * Date: 06/12/17 20:49
 * @author ionut
 */
interface IsteamContract
{
    public function setConfiguration(Configuration $config);

    /**
     * @return Configuration
     */
    public function getConfiguration();
}
