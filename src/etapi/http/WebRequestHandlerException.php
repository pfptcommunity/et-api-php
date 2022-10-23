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

/**
 * Base class for all web request handler exceptions.
 */
class WebRequestHandlerException extends ErrorException
{
}