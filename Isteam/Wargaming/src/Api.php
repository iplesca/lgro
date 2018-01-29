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
use Isteam\Wargaming\Platforms\Base;

/**
 * Class Api
 * @package Isteam\Wargaming
 *
 * @property $use
 * @method Platforms\Tanks tanks()
 * @method Platforms\Server server()
 */
class Api implements Definition
{
    use Platforms;

    const WG_OK    = 'ok';
    const WG_ERROR = 'error';
    const THROTTLE = 100000;

    /**
     * Client to make HTTP requests
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;
    /**
     * The URL for the WarGaming realm
     * @var string
     */
    protected $realm = '';
    /**
     * WarGaming application ID
     * @var null
     */
    protected $applicationId = null;
    /**
     * Redirect URL after a WarGaming login attempt
     * @var string
     */
    protected $redirectUri = '';
    /**
     * Holds loaded API classes
     * @var array
     */
    protected $platforms = [];
    /**
     * Holds last API response
     * @var array
     */
    protected $response = ['meta' => [], 'data' => []];
    /**
     * Name of the called API (Definition::PLATFORM*)
     * @var
     */
    protected $use;
    protected $accessToken = null;

    /**
     * Set the HTTP client (e.g. GuzzleHttp)
     * @param $client
     */
    public function setClient($client)
    {
        $this->httpClient = $client;
    }
    public function setAccessToken($token)
    {
        $this->accessToken = $token;
    }

    /**
     * Convenience method to bootstrap the library.
     * Sets the config values and the default GuzzleHttp client
     *
     * @param array $configArray
     */
    public function setup(array $configArray)
    {
        $this->loadConfig($configArray);

        $this->httpClient = new HttpClient([
            'base_uri' => $this->getBaseUrl()
        ]);
    }

    /**
     * Sets the configuration parameters.
     * $configArray = [
     *  'default_realm'  => value from Definition::WG_REALMS
     *  'application_id' => WarGaming API application id
     *  'redirect_uri'   => Redirect URL
     * ]
     * @param array $configArray
     * @throws Exception
     */
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

            if (false !== strpos($this->redirectUri, '$1')) {
                $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
                $this->redirectUri = str_replace('$1', $host . '/', $this->redirectUri);
            }
        }
    }

    /**
     * Catch-all method for the platform API calls
     *
     * @param $method string Platform
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        $api = $this->getPlatform($this->use);
        
        return call_user_func_array([$api, $method], $args);
    }

    /**
     * Get API response data
     * @return array
     */
    public function getResponseData()
    {
        return $this->response['data'];
    }

    /**
     * Get API response meta data
     * @return array
     */
    public function getResponseMeta()
    {
        return $this->response['meta'];
    }

    /**
     * Get the login url for WarGaming
     * @return string
     */
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

    /**
     * Get the realm URL (used to contain platform also)
     * @return string
     */
    protected function getBaseUrl()
    {
        return $this->realm .'/';
    }

    /**
     * Get a platform API singleton object (e.g. Tanks, Ships, Server etc.)
     * @param $name
     * @return Base
     */
    protected function getPlatform($name)
    {
        if (! isset($this->platforms[$name])) {
            $class = self::WG_CLASSES[$name];
            $this->platforms[$name] = new $class($this);
        }
        
        return $this->platforms[$name];
    }

    /**
     * Executes a HTTP request using the defined endpoint.
     * Adds `application_id` and `access_token` (if not added already)
     * Parses the response with parseResponse()
     *
     * @param Endpoint $endpoint
     * @return array
     * @throws Exception
     */
    public function execute(Endpoint $endpoint)
    {
        // add applicationId
        $params = $endpoint->getParams();
        $params['application_id'] = $this->applicationId;

        // set access token if none provided
        if (! isset($params['access_token'])) {
            if (! is_null($this->accessToken)) {
                $params['access_token'] = $this->accessToken;
            }
        } else {
            // was set explicitly to be discarded/not used
            if (false === $params['access_token']) {
                unset($params['access_token']);
            }
        }

        $endpointName = $this->use .'/' . $endpoint->getName() .'/';
        
        switch ($endpoint->getVerb()) {
            case 'get':
                $params = ['query' => $params];
                break;
            case 'post':
                $params = ['form_params' => $params];
                break;
        }

        try {
            $this->response['meta'] = [];
            $this->response['data'] = [];
            usleep(self::THROTTLE);
            $response = $this->httpClient->request($endpoint->getVerb(), $endpointName, $params);
        } catch (GuzzleException $e) {
            throw new Exception('guzzle', $e->getMessage(), $e->getCode());
        }
        
        $this->parseResponse($response);
        return $this->getResponseData();
    }

    /**
     * Parses the Wargaming response.
     * Throws Exceptions on communication/errors.
     *
     * @param $response
     * @throws Exception
     */
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







