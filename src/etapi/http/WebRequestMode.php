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
 * Predefined WebRequestMode
 * @enum
 *
 * @see WebRequestHandler
 */
enum WebRequestMode: int
{
    /**
     * The form constant is used to send form data.
     */
    case FORM = 0;
    /**
     * The data constant is used to send raw messages.
     */
    case DATA = 1;
}