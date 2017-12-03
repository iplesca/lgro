<?php

namespace WgApi;

class WgEndpoint 
{
    private $verb;
    private $name;
    private $responseFields = [];
    
    private $params = [];
    
    public function __construct($name, $verb)
    {
        $this->verb = $verb;
        $this->name = $name;
    }
    public function getVerb()
    {
        return $this->verb;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setParams($params)
    {
        $this->params = array_merge($this->params, $params);
    }
    public function getParams()
    {
        return $this->params;
    }
    public function setResponseFields($fields)
    {
        
    }
    public function getResponseFields()
    {
        
    }
    public function setPlayerId($accountIds)
    {
        if (!is_array($accountIds)) {
            $accountIds = [$accountIds];
        }
        $this->params['account_id'] = $accountIds;
    }
    protected function getPlayerId()
    {
        return $this->params['account_id'];
//        return ['account_id' => implode(',', $this->accountIds)];
    }
}