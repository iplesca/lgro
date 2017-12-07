<?php
namespace Isteam\Wargaming\Blueprints;

/**
 * This file is part of the isteam project.
 *
 * Date: 06/12/17 23:31
 * @author ionut
 */

abstract class Api
{
    private $endpoints = [];

    private function __construct() {}

    final public function addEndpoint($verb, $name, $params, $requiredParams)
    {
        $this->endpoints[] = [
            'verb'     => $verb,
            'name'     => $name,
            'params'   => $params,
            'required' =>$requiredParams
        ];
    }
    protected function useEndpoint($name, $params)
    {
        $this->validateParams($params);

        $result = false;

        try {
            $this->validateParams();
            $result = $this->client->request( $this->endpoint );

        } catch (WgException $e) {
            $this->setError($e->getCode(), $this->endpoint->getName(), $e->getMessage());
        }
        return $result;
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
}