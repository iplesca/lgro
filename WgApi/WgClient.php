<?php
namespace WgApi;

use GuzzleHttp\Client as GuzzleClient;
use WgApi\WgApiDefinition;
use WgApi\WgClientConfig;
use WgApi\Exceptions\WgException;
use GuzzleHttp\Exception\TransferException as GuzzleException;

class WgClient
{
    const WG_OK    = 'ok';
    const WG_ERROR = 'error';
    
    private $client;
    private $config;
    private $responseData = false;
    private $responseMeta = false;
    
    public function __construct(WgClientConfig $config)
    {
        $this->config = $config;
        
        $this->client = new GuzzleClient([
            'base_uri' => $config->getBaseUri()
        ]);
    }
    public function request(WgEndpoint $wgEndpoint)
    {
        $params['query'] = $this->prepareRequestParams($wgEndpoint);
        $params['debug'] = true;
        try {
            $response = $this->client->request($wgEndpoint->getVerb(), $wgEndpoint->getName() . '/', $params);
        } catch (GuzzleException $e) {
            // @todo handle Guzzle communication error
            throw new WgException('[Guzzle] ' . $e->getMessage(), $e->getCode());
        }
        $this->parseResponse($response);
        
        return $this->getLastResponseData();
    }
    public function getConfig()
    {
        return $this->config;
    }
    public function getLastResponseData()
    {
        return $this->responseData;
    }
    public function getLastResponseMeta()
    {
        return $this->responseMeta;
    }
    private function parseResponse($response)
    {
        $code = $response->getStatusCode();
        
        if (200 != $code) {
            // @todo handle HTTP code error from WG
            throw new WgException($response->getBody(), $code);
        }
        
        $jsonResponse = json_decode($response->getBody(), true);

        if ($jsonResponse) {
            if (self::WG_ERROR == $jsonResponse['status']) {
                throw new WgException($jsonResponse['error']['message'], $jsonResponse['error']['code']);
            }
            if (self::WG_OK == $jsonResponse['status']) {
                $this->responseData = $jsonResponse['data'];
                $this->responseMeta = $jsonResponse['meta'];
            }
        }
    }
    private function prepareRequestParams(WgEndpoint $wgEndpoint)
    {
        $result = $wgEndpoint->getParams();
        
        // set the application id
        $result['application_id'] = $this->config->getApplicationId();
        
        // set the response fields
        $result['fields'] = $wgEndpoint->getResponseFields();
        
        return $result;
    }
}