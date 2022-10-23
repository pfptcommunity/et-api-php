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

use Exception;
use IteratorAggregate;
use ArrayIterator;
use Traversable;
use Countable;

/**
 * Exception type thrown by the WebRequestHeaders class.
 *
 * @see WebRequestHeadersException
 */
class WebRequestHeadersException extends Exception
{
}

class WebRequestHeaders implements IteratorAggregate, Countable
{
    private array $_headers;

    public function __construct(array $headers = array())
    {
        $this->_headers = $headers;
    }

    public function clear()
    {
        $this->_headers = array();
    }

    public function get(string|int $key): ?string
    {
        return (array_key_exists($key, $this->_headers)) ? $this->_headers[$key] : null;
    }

    public function set(string|int $key, string|int $value): WebRequestHeaders
    {
        $this->_headers[$key] = $value;
        return $this;
    }

    public function delete(string|int $key): WebRequestHeaders
    {
        unset($this->_headers[$key]);
        return $this;
    }

    public function raw(): array
    {
        return $this->_headers;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->_headers);
    }

    public function count(): int
    {
        return count($this->_headers);
    }
}