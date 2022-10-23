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
 * Web request response class for HTTP request response information. When using CURL it is a
 * combination of curl_info as well as response message information.
 *
 * @see http://php.net/manual/en/function.curl-getinfo.php curl-getinfo
 */
class WebRequestResponse
{
    public readonly WebRequestHeaders $headers;
    public readonly WebRequestInfo $info;
    public readonly string $body;
    public readonly string $message;

    /**
     * Class constructor
     *
     * @param WebRequestHeaders $headers Web request headers to be sent
     * @param WebRequestInfo $info Web request information.
     * @param string $message Web response message.
     * @param string $body Web response body.
     *
     */
    public function __construct(WebRequestHeaders $headers, WebRequestInfo $info, string $message, string $body)
    {
        $this->headers = $headers;
        $this->info = $info;
        $this->message = $message;
        $this->body = $body;
    }
}