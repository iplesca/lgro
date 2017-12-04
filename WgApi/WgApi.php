<?php
namespace WgApi;

use Illuminate\Http\Request;
use WgApi\WgClient;
use WgApi\WgEndpoint;
use WgApi\Exceptions\WgException;

abstract class WgApi
{
    protected $platform = null;
    protected $definedEndpoints = null;
    
    private $endpoint = null;
    private $errors = [];
    private $client;
    
    final public function __construct(\WgApi\WgClientConfig $config)
    {
        if (empty($config->getApplicationId())) {
            throw new WgException('[WotApi] Application ID must defined');
        }
        if (is_null($this->definedEndpoints)) {
            throw new WgException('[WotApi] Must define endpoints for this WG API');
        }
        if (is_null($this->platform)) {
            throw new WgException('[WotApi] Must define a WG API platform');
        }
        $config->setPlatform($this->platform);
        
        $this->client = new WgClient($config);
    }
    
    private function validateParams()
    {
        $defEndpoint = $this->definedEndpoints[ $this->endpoint->getName() ];
        
        // keep only defined
        $params = array_intersect_key($this->endpoint->getParams(), array_flip($defEndpoint['params']));
        
        // check required
        foreach ($defEndpoint['required'] as $paramName ) {
            if (!isset($params[$paramName])) {
                throw new WgException($paramName, 10);
            }
        }
    }
    protected function createEndpoint($name)
    {
        if (!isset($this->definedEndpoints[$name])) {
            return false;
        }
        
        $this->endpoint = new WgEndpoint($name, $this->definedEndpoints[$name]['verb']);
        
        return true;
    }
    protected function setParams($params)
    {
        $this->endpoint->setParams($params);
    }
    private function setError($code, $endpointName, $message)
    {
        $this->errors[ $code ][] = [
            'path'    => $endpointName,
            'message' => $message
        ];
    }
    public function getErrors()
    {
        return $this->errors;
    }
    protected function execute()
    {
        $result = false;
        
        try {
            $this->validateParams();
            $result = $this->client->request( $this->endpoint );
            
        } catch (WgException $e) {
            $this->setError($e->getCode(), $this->endpoint->getName(), $e->getMessage());
        }
        return $result;
    }
    protected function getConfig()
    {
        return $this->client->getConfig();
    }
    public function getLoginUrl()
    {

        $url = $this->getConfig()->getBaseUri();
        $authEndpoint = 'auth/login/';

        $params = [
            'application_id' => $this->getConfig()->getApplicationId(),
            'redirect_uri' =>$this->getConfig()->getRedirectUri()
        ];

        return  $url. $authEndpoint .'?'. http_build_query($params);
    }
}