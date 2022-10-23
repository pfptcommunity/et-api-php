<?php
/**
 * This code was tested against PHP version 8.1.2
 *
 * @author Ludvik Jerabek
 * @package et-api-php
 * @version 1.0.0
 * @license MIT
 */

namespace etapi\rest;

/**
 * This is the base class for all web resources.
 *
 * @abstract
 */
abstract class Resource
{
    /**
     * This property refers to a concrete ClientResource.
     *
     * @var ClientResource
     */
    private ClientResource $client;
    /**
     * This property holds the name of the current resource URI.
     *
     * @var string Name of the current URI resource.
     */
    private string $uri;

    /**
     * Class constructor which should be called by all derived resources.
     *
     * @param ClientResource $client The root resource instance.
     * @param string $uri URI associated with this resource.
     */
    public function __construct(ClientResource $client, string $uri)
    {
        $this->client = $client;
        $this->uri = $uri;
    }

    /**
     * Method returns the base resource that contains a WebRequestHandler.
     *
     * @return ClientResource Client resource associated with the base API instance.
     */
    protected function get_client(): ClientResource
    {
        return $this->client;
    }

    /**
     * Method returns an array of parameters that were passed during construction.
     *
     * @return string The URI of the resource.
     *
     * @see Resource::__construct
     */
    protected function get_uri(): string
    {
        return $this->uri;
    }
}
