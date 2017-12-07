<?php
namespace Isteam\WargamingApi\Interfaces;

/**
 * This file is part of the isteam project.
 *
 * Date: 06/12/17 20:48
 * @author ionut
 */
use Isteam\WargamingApi\Contracts\IsteamContract;
use Isteam\WargamingApi\Configuration;

class WargamingClient implements WargamingApiDefinition
{
    protected $realm = '';
    protected $applicationId = null;
    protected $redirectUri = '';

    public function setup(Configuration $config)
    {
        $this->setConfiguration($config);

        $this->client = new GuzzleHttp([
            'base_uri' => $this->config->baseUri
        ]);
    }


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
    public function setApplicationId($applicationId)
    {
        $this->applicationId = $applicationId;
    }
    public function setRedirectUri($uri)
    {
        $this->redirectUri = $uri;
    }
}