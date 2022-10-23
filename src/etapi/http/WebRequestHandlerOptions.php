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


class WebRequestHandlerOptions
{
    private string $_user_agent;
    private WebProtocolVersion $_protocol_version;
    private int $_timeout;
    private int $_max_redirects;
    private bool $_follow_location;
    private WebRequestHeaders $_headers;
    private bool $_ssl_verify_peer;
    private ?string $_ssl_ca_file;

    public function __construct()
    {
        $this->_headers = new WebRequestHeaders();
        $this->_user_agent = "PHP/1.0";
        $this->_protocol_version = WebProtocolVersion::HTTP_VERSION_1_0;
        $this->_timeout = 60;
        $this->_max_redirects = 60;
        $this->_follow_location = true;
        $this->_ssl_verify_peer = false;
        $this->_ssl_ca_file = null;
    }

    public function set_user_agent(string $user_agent = "PHP/1.0"): WebRequestHandlerOptions
    {
        $this->_user_agent = $user_agent;
        return $this;
    }

    public function get_user_agent(): string
    {
        return $this->_user_agent;
    }

    public function set_protocol_version(WebProtocolVersion $protocol_version = WebProtocolVersion::HTTP_VERSION_1_0): WebRequestHandlerOptions
    {
        $this->_protocol_version = $protocol_version;
        return $this;
    }

    public function get_protocol_version(): WebProtocolVersion
    {
        return $this->_protocol_version;
    }

    public function set_timeout(int $timeout = 60): WebRequestHandlerOptions
    {
        $this->_timeout = $timeout;
        return $this;
    }

    public function get_timeout(): int
    {
        return $this->_timeout;
    }

    public function set_max_redirects(int $max_redirect = 60): WebRequestHandlerOptions
    {
        $this->_max_redirects = $max_redirect;
        return $this;
    }

    public function get_max_redirects(): ?int
    {
        return $this->_max_redirects;
    }

    public function set_follow_location(bool $follow_location = true): WebRequestHandlerOptions
    {
        $this->_follow_location = $follow_location;
        return $this;
    }

    public function get_follow_location(): bool
    {
        return $this->_follow_location;
    }

    public function set_ssl_verify(bool $ssl_verify_peer = false): WebRequestHandlerOptions
    {
        $this->_ssl_verify_peer = $ssl_verify_peer;
        return $this;
    }

    public function get_ssl_verify(): bool
    {
        return $this->_ssl_verify_peer;
    }

    public function set_ssl_ca_file(?string $ssl_ca_file): WebRequestHandlerOptions
    {
        $this->_ssl_ca_file = $ssl_ca_file;
        return $this;
    }

    public function get_ssl_ca_file(): ?string
    {
        return $this->_ssl_ca_file;
    }

    public function set_headers(WebRequestHeaders $headers): WebRequestHandlerOptions
    {
        $this->_headers = $headers;
        return $this;
    }

    public function get_headers(): WebRequestHeaders
    {
        return $this->_headers;
    }
}