<?php
/**
 * This code was tested against PHP version 8.1.2
 *
 * @author Ludvik Jerabek
 * @package et-api-php
 * @version 1.0.0
 * @license MIT
 */

namespace etapi\http;

use ErrorException;
use Exception;

/**
 * Exception type thrown by the RequestHandlerFactory class.
 *
 * @see WebRequestHandlerFactory
 */
class WebRequestHandlerFactoryException extends ErrorException
{
}

/**
 * Class factory creates instances of a concrete WebRequestHandler. If CURL is installed, the factory will return
 * a WebRequestHandlerCurl instance. If openssl is not installed,
 * the class will throw a WebRequestHandlerFactoryException.
 *
 * @abstract
 *
 * @see WebRequestHandlerCurl
 * @see WebRequestHandler
 */
abstract class WebRequestHandlerFactory
{
    /**
     * This static method creates an instance of a concrete WebRequestHandler.
     *
     * @param string $uri HTTP request URL.
     * @param WebRequestHandlerOptions $options HTTP request headers and options.
     *
     * @return WebRequestHandler
     *
     * @throws WebRequestHandlerFactoryException
     * @throws WebRequestHandlerCurlException
     *
     * @see WebRequestHandlerOptions
     *
     */
    public static function Create(string $uri, WebRequestHandlerOptions $options): WebRequestHandler
    {
        try {
            if (!in_array('openssl', get_loaded_extensions())) {
                throw new WebRequestHandlerFactoryException("The OpenSSL extension is required but not currently enabled.");
            }
            // Use CURL if it is installed
            if (in_array('curl', get_loaded_extensions())) {
                return new WebRequestHandlerCurl($uri, $options);
            } else {
                throw new WebRequestHandlerFactoryException("WebRequestHandlerCurl requires CURL, please install CURL.");
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
