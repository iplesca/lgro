<?php
namespace Isteam\Wargaming\Blueprints;

/**
 * This file is part of the isteam project.
 *
 * Date: 06/12/17 23:30
 * @author ionut
 */
use Isteam\Wargaming\Blueprints\Api as Blueprint;

class Tanks extends Blueprint
{
    public function __construct()
    {
        $this->addEndpoint(
            'get', 'account/list',
            ['search', 'limit', 'type', 'language'],
            ['search']
        );
        $this->addEndpoint(
            'get', 'account/info',
            ['account_id', 'access_token', 'extra', 'fields', 'language'],
            ['account_id']
        );
    }
}