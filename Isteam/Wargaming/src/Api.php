<?php
namespace Isteam\Wargaming;

/**
 * This file is part of the isteam project.
 *
 * Date: 06/12/17 20:45
 * @author ionut
 */
use Isteam\Wargaming\Exceptions\Exception;
use Isteam\Wargaming\ApiDefinition as Definition;

class Api implements Definition
{
    use PlatformShortcuts;

    /**
     * Client to make HTTP requests
     */
    protected $httpClient;

    protected $realm = '';
    protected $applicationId = null;
    protected $redirectUri = '';
    protected $platforms = [];

    public function setClient($client)
    {
        $this->httpClient = $client;
    }
    public function useApi(array $wgApis)
    {
        foreach ($wgApis as $api) {
            if (! in_array($api, Definition::WG_PLATFORMS)) {
                throw new Exception("Platform `$api` not defined");
            }

            $this->loadApi($api);
        }
    }
    private function loadApi($api)
    {
        $this->platforms[$api] = new (Definition::WG_CLASSES[$api]);
    }
    public function setup(array $configArray)
    {
        $this->loadConfig($configArray);

//        $this->client = new GuzzleHttp([
//            'base_uri' => $this->config->baseUri
//        ]);
    }

    public function loadConfig(array $configArray)
    {
        if (isset($configArray['realm'])) {
            $name = $configArray['realm'];
            $realms = Definition::WG_REALMS;

            if (isset($realms[$name])) {
                $this->realm = $realms[$name];
            } else {
                throw new Exception("Realm `$name` is not defined");
            }
        }
        if (isset($configArray['applicationId'])) {
            $this->applicationId = $configArray['applicationId'];
        }
        if (isset($configArray['redirectUri'])) {
            $this->redirectUri = $configArray['redirectUri'];
        }
    }
}







