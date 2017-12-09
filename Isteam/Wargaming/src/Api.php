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
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class Api
 * @package Isteam\Wargaming
 *
 * @property $use
 */
class Api implements Definition
{
    use PlatformShortcuts;

    const WG_OK    = 'ok';
    const WG_ERROR = 'error';

    /**
     * Client to make HTTP requests
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    protected $realm = '';
    protected $applicationId = null;
    protected $redirectUri = '';
    protected $platforms = [];
    protected $response = ['meta' => [], 'data' => []];
    protected $use;

    public function setClient($client)
    {
        $this->httpClient = $client;
    }
    public function setup(array $configArray)
    {
        $this->loadConfig($configArray);

        $this->httpClient = new HttpClient([
            'base_uri' => $this->getBaseUrl()
        ]);
    }
    public function loadConfig(array $configArray)
    {
        if (isset($configArray['default_realm'])) {
            $name = $configArray['default_realm'];
            $realms = Definition::WG_REALMS;

            if (isset($realms[$name])) {
                $this->realm = $realms[$name];
            } else {
                throw new Exception("Realm `$name` is not defined");
            }
        }
        if (isset($configArray['application_id'])) {
            $this->applicationId = $configArray['application_id'];
        }
        if (isset($configArray['redirect_uri'])) {
            $this->redirectUri = $configArray['redirect_uri'];
        }
    }
    public function __call($method, $args)
    {
        // get endpoint
        $api = $this->getPlatform($this->use);
        
        $endpoint = call_user_func_array([$api, $method], $args);
        return $this->execute($endpoint);
    }
    public function getResponseData()
    {
        return $this->response['data'];
    }
    public function getResponseMeta()
    {
        return $this->response['meta'];
    }
    public function getLoginUrl()
    {
        $url = $this->getBaseUrl() . Definition::PLATFORM_WOTANKS;
        $authEndpoint = '/auth/login/';

        $params = [
            'application_id' => $this->applicationId,
            'redirect_uri' =>$this->redirectUri
        ];
        return $url. $authEndpoint .'?'. http_build_query($params);
        
    }
    protected function getBaseUrl()
    {
        return $this->realm .'/';
    }
    protected function getPlatform($name)
    {
        if (! isset($this->platforms[$name])) {
            $class = self::WG_CLASSES[$name];
            $this->platforms[$name] = new $class();
        }
        
        return $this->platforms[$name];
    }
    protected function execute(Endpoint $ep)
    {
        // add applicationId
        $params = $ep->getParams();
        $params['application_id'] = $this->applicationId;

        $endpoint = $this->use .'/' . $ep->getName() .'/';
        
        switch ($ep->getVerb()) {
            case 'get': {
                $params = ['query' => $params];
                break;
            }
            case 'post': {
                $params = ['form_params' => $params];
                break;
            }
        }
        $params['debug'] = true;
        try {
            $this->response['meta'] = [];
            $this->response['data'] = [];
            $response = $this->httpClient->request($ep->getVerb(), $endpoint, $params);
        } catch (GuzzleException $e) {
            throw new Exception('guzzle', $e->getMessage(), $e->getCode());
        }
        
        $this->parseResponse($response);
        return $this->getResponseData();
    }
    protected function parseResponse($response)
    {
        $code = $response->getStatusCode();

        if (200 != $code) {
            throw new Exception('wargaming-api', $response->getBody(), $code);
        }

        $jsonResponse = json_decode($response->getBody(), true);
        
        if (! is_null($jsonResponse)) {
            if (self::WG_ERROR == $jsonResponse['status']) {
                throw new Exception('wargaming-api', $jsonResponse['error']['message'], $jsonResponse['error']['code']);
            }
            if (self::WG_OK == $jsonResponse['status']) {
                $this->response = $jsonResponse;
            }
        }
    }
}







