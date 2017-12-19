<?php
namespace Isteam\Wargaming;

/**
 * This file is part of the isteam project.
 *
 * Date: 07/12/17 09:21
 * @author ionut
 */

class Endpoint
{
    private $verb;
    private $name;
    private $params;
    
    public function __construct($verb, $name, $params)
    {
        $this->verb = $verb;
        $this->name = $name;
        $this->params = $params;
    }
    public function getVerb()
    {
        return $this->verb;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getParams()
    {
        return $this->params;
    }
}