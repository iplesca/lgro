<?php
namespace Isteam\Wargaming\Platforms;
use Isteam\Wargaming\Api;
use Isteam\Wargaming\Endpoint;

/**
 * This file is part of the isteam project.
 *
 * Date: 09/12/17 11:14
 * @author ionut
 */

class Base
{
    /**
     * Holds an instance of the Wargaming API object
     * @var Api
     */
    private $api;

    /**
     * Base constructor.
     * @param Api $api
     */
    final public function __construct(Api $api)
    {
        $this->api = $api;
    }

    /**
     * Wrapper for the api execute() method.
     * Creates an Endpoint.
     *
     * @param $verb
     * @param $name
     * @param $params
     * @return array
     */
    final public function execute($verb, $name, $params)
    {
        return $this->api->execute(new Endpoint($verb, $name, $params));
    }

    /**
     * Flatten (implode) an array of ids (comma separated)
     * @param mixed $ids
     * @return mixed
     */
    protected function flatten($ids)
    {
        $result = $ids;
        if (is_array($ids)) {
            $result = implode(',', $ids);
        }
        return $result;
    }
}