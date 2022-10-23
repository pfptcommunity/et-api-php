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

enum SortBy: string
{
    case IP = "ip";
    case LAST_SEEN = "last_seen";
}

enum SortDirection: string
{
    case ASC = "ASC";
    case DESC = "DESC";
}

/**
 * Class is dynamically loaded by Client to provide report queries to the Web Service
 *
 * @link http://apidocs.emergingthreats.net/ Emerging Threats API Guide
 */
class Sid extends Resource
{
    public function ips(SortBy $sort_by = SortBy::IP, SortDirection $sort_direction = SortDirection::DESC)
    {
        $params = array('sortBy' => $sort_by->value, 'sortDirection' => $sort_direction->value);
        return $this->get_client()->retrieve($this->get_uri() . "/ips", $params);
    }

    public function domains()
    {
        return $this->get_client()->retrieve($this->get_uri() . "/domains");
    }

    public function samples()
    {
        return $this->get_client()->retrieve($this->get_uri() . "/samples");
    }

    public function text()
    {
        return $this->get_client()->retrieve($this->get_uri() . "/text");
    }

    public function documentation()
    {
        return $this->get_client()->retrieve($this->get_uri() . "/documentation");
    }

    public function references()
    {
        return $this->get_client()->retrieve($this->get_uri() . "/references");
    }

    public function __invoke()
    {
        return $this->get_client()->retrieve($this->get_uri());
    }
}