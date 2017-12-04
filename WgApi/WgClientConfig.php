<?php
namespace WgApi;

use WgApi\Exceptions\WgException;
use WgApi\Interfaces\WgApiDefinition;

class WgClientConfig implements WgApiDefinition
{
    protected $realm = '';
    protected $platform = '';
    protected $applicationId = null;
    protected $redirectUri = '';

    public function setRealm($realmName)
    {
        if (isset(self::WG_REALMS[$realmName])) {
            $this->realm = self::WG_REALMS[$realmName];
        } else {
            throw new WgException("[WgClientConfig] Realm `$realmName` is not defined");
        }
    }
    public function setPlatform($platformName)
    {
        if (in_array($platformName, self::WG_PLATFORMS)) {
            $this->platform = $platformName;
        } else {
            throw new WgException("[WgClientConfig] Game platform `$platformName` is not defined");
        }
    }
    public function setApplicationId($applicationId)
    {
        if (!empty($applicationId)) {
            $this->applicationId = $applicationId;
        } else {
            throw new WgException("[WgClientConfig] Application ID must be provided");
        }
    }
    public function setRedirectUri($uri)
    {
        $this->redirectUri = $uri;
    }
    public function getApplicationId()
    {
        return $this->applicationId;
    }
    public function getBaseUri()
    {
        return $this->realm .'/'. $this->platform . '/';
    }
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }
}