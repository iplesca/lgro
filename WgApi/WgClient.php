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
        
        $jsonResponse = json_decode($response->getBody());

        if ($jsonResponse) {
            if (self::WG_ERROR == $jsonResponse->status) {
                throw new WgException($jsonResponse->error->message, $jsonResponse->error->code);
            }
            if (self::WG_OK == $jsonResponse->status) {
                $this->responseData = $jsonResponse->data;
                $this->responseMeta = $jsonResponse->meta;
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
/*
interface WotApiAction 
{
    const ACT_ACCOUNT         = 'account';
    const ACT_AUTHENTICATION  = 'auth';
    const ACT_STRONGHOLD      = 'stronghold';
    const ACT_GLOBALMAP       = 'globalmap';
    const ACT_TANKOPEDIA      = 'encylopedia';
    const ACT_PLAYER_RATINGS  = 'ratings';
    const ACT_CLAN_RATINGS    = 'clanratings';
    const ACT_PLAYER_VEHICLES = 'tanks';
    const ACT_PERMANENT_TEAMS = 'regularteams';
}
class WotApiAccount extends WotApiAction
{
    protected $verb = 'get';
    protected $name = self::ACT_ACCOUNT;
    
    const OP_LIST        = 'list';
    const OP_INFO        = 'info';
    const OP_TANKS       = 'tanks';
    const OP_ACHIEVEMENT = 'achievements';
    
    protected $mandatoryParams = [
        self::OP_LIST => ['search'],
        self::OP_INFO => ['account_id'],
        self::OP_TANKS => ['account_id'],
        self::OP_ACHIEVEMENT => ['account_id'],
    ];
}
*/