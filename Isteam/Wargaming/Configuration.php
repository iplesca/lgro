<?php
namespace Isteam\WargamingApi;

/**
 * This file is part of the isteam project.
 *
 * Date: 06/12/17 20:55
 * @author ionut
 */

use Isteam\WargamingApi\Exceptions\Exception as Exception;

/**
 * Class Configuration
 * @package Isteam\Wargaming
 *
 * @property $redirectUri
 * @property $baseUri
 */
class Configuration implements \ArrayAccess, ApiDefinition
{
    protected $realm = '';
//    protected $platform = '';
    protected $applicationId = null;
    protected $redirectUri = '';

    public function from(array $configArray)
    {
        if (isset($configArray['realm'])) {
            $this->setRealm($configArray['realm']);
        }
        if (isset($configArray['applicationId'])) {
            $this->setApplicationId($configArray['applicationId']);
        }
        if (isset($configArray['redirectUri'])) {
            $this->setRedirectUri($configArray['redirectUri']);
        }
    }
    public function setRealm($realmName)
    {
        if (isset(self::WG_REALMS[$realmName])) {
            $this->realm = self::WG_REALMS[$realmName];
        } else {
            throw new Exception("Realm `$realmName` is not defined");
        }
    }
//    public function setPlatform($platformName)
//    {
//        if (in_array($platformName, self::WG_PLATFORMS)) {
//            $this->platform = $platformName;
//        } else {
//            throw new Exception("Game platform `$platformName` is not defined");
//        }
//    }
    public function setApplicationId($applicationId)
    {
        $this->applicationId = $applicationId;
    }
    public function setRedirectUri($uri)
    {
        $this->redirectUri = $uri;
    }
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }
    public function offsetGet($offset)
    {
        if (in_array($offset, ['redirectUri', 'applicationId', 'realm', 'platform'])) {
            return $this->{$offset};
        }
        if ('baseUri') {
            return $this->realm .'/'. $this->platform;
        }
    }
    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
}