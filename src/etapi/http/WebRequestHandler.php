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

/**
 * Abstract class should be inherited by a concrete web request handler.
 *
 * @abstract
 *
 * @see WebRequestHandlerCurl
 * @see WebRequestHandlerFactory
 *
 */
abstract class WebRequestHandler
{
    /**
     * This property is set by parsing the URI string passed to the constructor.
     *
     * @var string The value is either http or https.
     *
     * @see http://php.net/manual/en/function.parse-url.php parse_url
     */
    protected string $scheme;
    /**
     * This property is set by parsing the URI string passed to the constructor.
     *
     * @var string The value is a hostname or IP.
     *
     * @see http://php.net/manual/en/function.parse-url.php parse_url
     */
    protected string $host;
    /**
     * This property is set by parsing the URI string passed to the constructor.
     *
     * @var ?int The value is a port number.
     *
     * @see http://php.net/manual/en/function.parse-url.php parse_url
     */
    protected ?int $port = null;
    /**
     * This property is set during class construction and contains options validated against default_options.
     *
     * @var WebRequestHandlerOptions The value is an array of allowed options.
     *
     * @see http://php.net/manual/en/function.parse-url.php parse_url
     */
    private WebRequestHandlerOptions $default_options;

    /**
     * Constructor is called by the derived object.
     *
     * @param string $uri HTTP request URL.
     * @param WebRequestHandlerOptions $options HTTP request headers and options.
     *
     * @return void
     *
     * @todo Replace _validate_options with default php filter_var_array
     */
    public function __construct(string $uri, WebRequestHandlerOptions $options)
    {
        foreach (parse_url($uri) as $name => $value) {
            $this->$name = $value;
        }
        $this->default_options = $options;
    }

    /**
     * Method returns the default_options class member.
     *
     * @return WebRequestHandlerOptions Returns a list of the validated options.
     *
     * @see WebRequestHandler::$allowed_options allowed_options
     */
    final protected function get_default_options(): WebRequestHandlerOptions
    {
        return $this->default_options;
    }

    abstract public function get(
        string $res,
        ?WebRequestHeaders $headers,
        mixed $parameters,
        string $output_file,
        WebRequestMode $mode
    ): WebRequestResponse;

    abstract public function put(
        string $res,
        ?WebRequestHeaders $headers,
        mixed $parameters,
        string $output_file,
        WebRequestMode $mode
    ): WebRequestResponse;

    abstract public function post(
        string $res,
        ?WebRequestHeaders $headers,
        mixed $parameters,
        string $output_file,
        WebRequestMode $mode
    ): WebRequestResponse;

    abstract public function delete(
        string $res,
        ?WebRequestHeaders $headers,
        mixed $parameters,
        string $output_file,
        WebRequestMode $mode
    ): WebRequestResponse;
}
