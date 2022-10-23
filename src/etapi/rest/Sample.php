<?php
/**
 * This code was tested against PHP version 8.1.9
 *
 * @author Ludvik Jerabek
 * @package et-api-php
 * @version 1.0.0
 * @license MIT
 */

namespace etapi\rest;

use etapi\rest\Resource;

/**
 * Class is dynamically loaded by Client to provide report queries to the Web Service
 *
 * @link http://apidocs.emergingthreats.net/ Emerging Threats API Guide
 */
class Sample extends Resource
{
    public function connections()
    {
        return $this->get_client()->retrieve($this->get_uri() . "/connections");
    }

    public function dns()
    {
        return $this->get_client()->retrieve($this->get_uri() . "/dns");
    }

    public function events()
    {
        return $this->get_client()->retrieve($this->get_uri() . "/events");
    }

    public function http()
    {
        return $this->get_client()->retrieve($this->get_uri() . "/http");
    }

    public function __invoke()
    {
        return $this->get_client()->retrieve($this->get_uri());
    }
}