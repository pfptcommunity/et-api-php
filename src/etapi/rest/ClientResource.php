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

use ErrorException;
use Exception;
use etapi\http\WebRequestHandler;
use etapi\http\WebRequestHandlerFactory;
use etapi\http\WebRequestHandlerOptions;
use etapi\http\WebRequestHeaders;
use etapi\http\WebRequestResponse;
use etapi\http\WebRequestMode;

/**
 * ClientResource exception class is thrown by ClientException.
 */
class ClientResourceException extends ErrorException
{
}

/**
 * This is the base class for every API client implantation.
 *
 * @abstract
 */
abstract class ClientResource extends Resource
{
    /**
     * This property stores a web request handler instance.
     *
     * @var WebRequestHandler This handler is used by all webservice calls.
     */
    private WebRequestHandler $http;

    /**
     * Web services API client constructor.
     *
     * @param string $base_uri The base URI path eg. http://my.server.com
     * @param string $base_resource The base URI resource eg. api/1.0
     * @param WebRequestHandlerOptions $request_handler_options
     *
     * @throws ClientResourceException
     */
    public function __construct(string $base_uri, string $base_resource, WebRequestHandlerOptions $request_handler_options)
    {
        try {
            $this->http = WebRequestHandlerFactory::Create(
                $base_uri,
                $request_handler_options
            );
        } catch (Exception $ex) {
            throw new ClientResourceException("Could not create web request handler instance", 0, 1, __FILE__, __LINE__, $ex);
        }
        parent::__construct($this, $base_resource);
    }

    /**
     * Method retrieves the internal WebRequestHandler object.
     *
     * @return WebRequestHandler Returns the web request handler associated with this ClientResource.
     */
    public function get_request_handler(): WebRequestHandler
    {
        return $this->http;
    }

    /**
     * This CRUD method performs HTTP POST.
     *
     * @param string $uri The root resource instance.
     * @param array $params Data to send.
     * @param ?WebRequestHeaders $headers Http headers to send.
     * @param string $to_file If defined, the HTTP response is stored to the named file.
     * @param WebRequestMode $mode WebRequestMode::DATA or WebRequestMode::FORM
     * @param ?Callable $callback Callback function for custom WebRequestResponse processing.
     *
     * @return mixed
     * @throws ClientResourceException
     *
     */
    public function create(
        string $uri = '',
        array $params = array(),
        ?WebRequestHeaders $headers = null,
        string $to_file = '',
        WebRequestMode $mode = WebRequestMode::FORM,
        ?callable $callback = null
    ): mixed {
        try {
            $response = $this->http->post($uri, $headers, $params, $to_file, $mode);
            if (is_callable($callback)) {
                return call_user_func($callback, $response);
            }
            return $this->process_crud_response($response);
        } catch (Exception $ex) {
            throw new ClientResourceException("Webservice CRUD create call failed", $ex->getCode(), 1, __FILE__, __LINE__, $ex);
        }
    }

    /**
     * This CRUD method performs HTTP GET.
     *
     * @param string $uri The root resource instance.
     * @param array $params Data to send.
     * @param ?WebRequestHeaders $headers Http headers to send.
     * @param string $to_file If defined, the HTTP response is stored to the named file.
     * @param WebRequestMode $mode WebRequestMode::DATA or WebRequestMode::FORM
     * @param ?Callable $callback Callback function for custom WebRequestResponse processing.
     *
     * @return mixed
     * @throws ClientResourceException
     *
     */
    public function retrieve(
        string $uri = '',
        array $params = array(),
        WebRequestHeaders $headers = null,
        string $to_file = '',
        WebRequestMode $mode = WebRequestMode::FORM,
        ?callable $callback = null
    ): mixed {
        try {
            $response = $this->http->get($uri, $headers, $params, $to_file, $mode);
            if (is_callable($callback)) {
                return call_user_func($callback, $response);
            }
            return $this->process_crud_response($response);
        } catch (Exception $ex) {
            throw new ClientResourceException("Webservice CRUD retrieve call failed", $ex->getCode(), 1, __FILE__, __LINE__, $ex);
        }
    }

    /**
     * This CRUD method performs HTTP PUT.
     *
     * @param string $uri The root resource instance.
     * @param array $params Data to send.
     * @param ?WebRequestHeaders $headers Http headers to send.
     * @param string $to_file If defined, the HTTP response is stored to the named file.
     * @param WebRequestMode $mode WebRequestMode::DATA or WebRequestMode::FORM
     * @param ?Callable $callback Callback function for custom WebRequestResponse processing.
     *
     * @return mixed
     * @throws ClientResourceException
     *
     */
    public function update(
        string $uri = '',
        array $params = array(),
        WebRequestHeaders $headers = null,
        string $to_file = '',
        WebRequestMode $mode = WebRequestMode::FORM,
        ?callable $callback = null
    ): mixed {
        try {
            $response = $this->http->put($uri, $headers, $params, $to_file, $mode);
            if (is_callable($callback)) {
                return call_user_func($callback, $response);
            }
            return $this->process_crud_response($response);
        } catch (Exception $ex) {
            throw new ClientResourceException("Webservice CRUD update call failed", $ex->getCode(), 1, __FILE__, __LINE__, $ex);
        }
    }

    /**
     * This CRUD method performs HTTP DELETE.
     *
     * @param string $uri The root resource instance.
     * @param array $params Data to send.
     * @param ?WebRequestHeaders $headers Http headers to send.
     * @param string $to_file If defined, the HTTP response is stored to the named file.
     * @param WebRequestMode $mode WebRequestMode::DATA or WebRequestMode::FORM
     * @param ?Callable $callback Callback function for custom WebRequestResponse processing.
     *
     * @return mixed
     * @throws ClientResourceException
     *
     */
    public function delete(
        string $uri = '',
        array $params = array(),
        ?WebRequestHeaders $headers = null,
        string $to_file = '',
        WebRequestMode $mode = WebRequestMode::FORM,
        ?callable $callback = null
    ): mixed {
        try {
            $response = $this->http->delete($uri, $headers, $params, $to_file, $mode);
            if (is_callable($callback)) {
                return call_user_func($callback, $response);
            }
            return $this->process_crud_response($response);
        } catch (Exception $ex) {
            throw new ClientResourceException("Webservice CRUD delete call failed", $ex->getCode(), 1, __FILE__, __LINE__, $ex);
        }
    }

    /**
     * Method is called by internal CRUD methods to process a web request response.
     *
     * @param WebRequestResponse $response
     *
     * @return mixed
     */
    abstract protected function process_crud_response(WebRequestResponse $response): mixed;
}
