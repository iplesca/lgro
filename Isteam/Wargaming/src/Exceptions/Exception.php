<?php
namespace Isteam\Wargaming\Exceptions;
/**
 * This file is part of the isteam project.
 *
 * Date: 06/12/17 21:00
 * @author ionut
 */

class Exception extends \Exception
{
    private $type;
    
    public function __construct($type = 'api', $message = "", $code = 0, \Exception $previous = null)
    {
        $this->type = $type;
        \Exception::__construct($message, $code, $previous);
    }
}