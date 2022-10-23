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
class Ip extends Resource
{
    public function reputation()
    {
        return $this->get_client()->retrieve($this->get_uri() . "/reputation");
    }

    public function geoloc()
    {
        return $this->get_client()->retrieve($this->get_uri() . "/geoloc");
    }

    public function domains()
    {
        return $this->get_client()->retrieve($this->get_uri() . "/domains");
    }

    public function samples()
    {
        return $this->get_client()->retrieve($this->get_uri() . "/samples");
    }

    public function urls()
    {
        return $this->get_client()->retrieve($this->get_uri() . "/urls");
    }

    public function events()
    {
        return $this->get_client()->retrieve($this->get_uri() . "/events");
    }
}
