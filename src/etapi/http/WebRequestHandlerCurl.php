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

use CurlHandle;
use Exception;

/**
 * Required for WebRequestMode Enum
 */

/**
 * Web request handler exception class is thrown by WebRequestHandlerCurl.
 */
class WebRequestHandlerCurlException extends WebRequestHandlerException
{
}

/**
 * Web request handler class which uses php-curl for HTTP requests.
 */
class WebRequestHandlerCurl extends WebRequestHandler
{
    /**
     * This property stores the HTTP response results.
     *
     * @var array Results of last transmission eg. error_code, error_message
     */
    private array $last_status;
    /**
     * This property stores all the response headers.
     *
     * @var WebRequestHeaders Header information for last transmission.
     */
    private WebRequestHeaders $last_headers;
    /**
     * This property is the curl handle to be reused or deleted
     *
     * @var CurlHandle Header information for last transmission.
     */
    private CurlHandle $curl;

    /**
     * Class constructor calls parent class constructor.
     *
     * @param string $uri HTTP request URL.
     * @param WebRequestHandlerOptions $options HTTP request headers and options.
     *
     * @throws WebRequestHandlerCurlException
     */
    public function __construct(string $uri, WebRequestHandlerOptions $options)
    {
        parent::__construct($uri, $options);
        $this->last_headers = new WebRequestHeaders();
        $this->curl = curl_init();
        if (!$this->curl) {
            throw new WebRequestHandlerCurlException('Unable to initialize cURL');
        }
    }

    public function __destruct()
    {
        if ($this->curl) {
            curl_close($this->curl);
        }
    }

    /**
     * Method converts the parent's default_options into CURL specific options.
     *
     * @param string $res A URL resource.
     *
     * @return array Returns an array of CURL options.
     */
    private function create_context(string $res): array
    {
        $options = $this->get_default_options();
        $curl_opts = array();
        $curl_opts[CURLOPT_URL] = "$this->scheme://$this->host$res";
        if ($this->port) {
            $curl_opts[CURLOPT_PORT] = $this->port;
        }
        // Header is parsed via header callback
        $curl_opts[CURLOPT_HEADER] = false;
        // Header parsing callback
        $curl_opts[CURLOPT_HEADERFUNCTION] = array($this, '_read_header');
        // Return the response (Even File Contents)
        $curl_opts[CURLOPT_RETURNTRANSFER] = true;

        $curl_opts[CURLOPT_USERAGENT] = $options->get_user_agent();
        $curl_opts[CURLOPT_HTTP_VERSION] = $options->get_protocol_version()->value;
        $curl_opts[CURLOPT_TIMEOUT] = $options->get_timeout();
        $curl_opts[CURLOPT_MAXREDIRS] = $options->get_max_redirects();
        $curl_opts[CURLOPT_FOLLOWLOCATION] = $options->get_follow_location();

        $curl_opts[CURLOPT_HTTPHEADER] = array();
        foreach ($options->get_headers() as $k => $v) {
            $curl_opts[CURLOPT_HTTPHEADER][] = "$k: $v";
        }

        $curl_opts[CURLOPT_SSL_VERIFYPEER] = $options->get_ssl_verify();
        $curl_opts[CURLOPT_SSL_VERIFYHOST] = $options->get_ssl_verify();

        if ($options->get_ssl_ca_file() != null) {
            $curl_opts[CURLOPT_CAINFO] = $options->get_ssl_ca_file();
        }

        return $curl_opts;
    }

    /**
     * Method parses the HTTP response header.
     *
     * @param CurlHandle $curl A curl object.
     * @param string $string A header line.
     *
     * @return integer Returns the size of the current header string.
     *
     * @see http://php.net/manual/en/function.curl-setopt.php CURLOPT_HEADERFUNCTION
     */
    private function _read_header(CurlHandle $curl, string $string): int
    {
        $len = strlen($string);
        $string = preg_replace("/[\r\n]/", "", $string);
        $details = explode(':', $string, 2);
        if (count($details) == 2) {
            $key = trim($details[0]);
            $value = trim($details[1]);
            $this->last_headers->set($key, $value);
        } else {
            if (preg_match('#(HTTP/\d+\.\d+) (\d+) ?(.*)#', $string, $matches) === 1) {
                $this->last_status = $matches;
            }
        }
        return $len;
    }

    /**
     * Method executes the HTTP request.
     *
     * @param array $opts An array of curl options.
     * @param string $to_file If a filename is passed the HTTP response is stored to the named file.
     *
     * @return WebRequestResponse
     *
     * @throws WebRequestHandlerCurlException
     */
    private function _transmit(array &$opts, string $to_file): WebRequestResponse
    {
        // Clear the last headers and status
        $this->last_headers->clear();
        $this->last_status = array();

        // Capture response to file?
        if (!empty($to_file)) {
            //CURLOPT_BINARYTRANSFER?
            $opts[CURLOPT_FILE] = @fopen($to_file, 'w');
            if (!$opts[CURLOPT_FILE]) {
                $e = error_get_last();
                throw new WebRequestHandlerCurlException("Failed to open curl output file: " . $e['message'], 0, $e['type'], $e['file'], $e['line']);
            }

            // Most likely we are downloading a file that could be large which may take a while
            set_time_limit(0);
        }

        try {
            if ($this->curl) {
                if (curl_setopt_array($this->curl, $opts)) {
                    $response = curl_exec($this->curl);
                    $message = '';

                    // Was the call successful?
                    if (!curl_errno($this->curl)) {
                        $info = new WebRequestInfo($this->curl);
                        if (array_key_exists(3, $this->last_status)) {
                            $message = $this->last_status[3];
                        }
                        return new WebRequestResponse($this->last_headers, $info, $message, $response);
                    } else {
                        throw new WebRequestHandlerCurlException(curl_error($this->curl));
                    }
                } else {
                    throw new WebRequestHandlerCurlException(curl_error($this->curl));
                }
            } else {
                throw new WebRequestHandlerCurlException('Unable to validate cURL handle is a resource');
            }
        } catch (Exception $ex) {
            if ($this->curl) {
                curl_close($this->curl);
            }
            throw new WebRequestHandlerCurlException("Transmission failed", 0, 1, __FILE__, __LINE__, $ex);
        } finally {
            if (array_key_exists(CURLOPT_FILE, $opts) && is_resource($opts[CURLOPT_FILE])) {
                fclose($opts[CURLOPT_FILE]);
                set_time_limit((defined("max_execution_time")) ? max_execution_time : 30);
            }
            if (array_key_exists(CURLOPT_INFILE, $opts) && is_resource($opts[CURLOPT_INFILE])) {
                fclose($opts[CURLOPT_INFILE]);
            }
        }
    }

    /**
     * Method prepares settings for a GET request.
     *
     * @param string $res URI resouce.
     * @param ?WebRequestHeaders $headers Web request headers to be sent
     * @param mixed $parameters Parameters passed to the get call can be CurlFile, Array, or other.
     * @param string $output_file Used when storing output to a file
     * @param WebRequestMode $mode WebRequestMode::DATA or WebRequestMode::FORM
     *
     * @return WebRequestResponse
     *
     * @throws WebRequestHandlerCurlException
     */
    public function get(string $res, ?WebRequestHeaders $headers, mixed $parameters, string $output_file, WebRequestMode $mode): WebRequestResponse
    {
        // Process default options
        $opts = $this->create_context($res);

        // Append additional headers as needed
        if ($headers != null) {
            foreach ($headers as $k => $v) {
                $opts[CURLOPT_HTTPHEADER][] = "$k: $v";
            }
        }

        try {
            if ($mode == WebRequestMode::DATA) {
                $opts[CURLOPT_CUSTOMREQUEST] = "GET";
                $opts[CURLOPT_POST] = true;
                // Put in data mode must be CURLFile type since we will send a file as the output
                if (!is_object($parameters) || strcmp('CURLFile', get_class($parameters)) != 0) {
                    throw new WebRequestHandlerCurlException("Invalid arguments {$opts[CURLOPT_CUSTOMREQUEST]} WebRequestMode::DATA requires CURLFile type");
                }
                $file = $parameters->getFilename();
                if (file_exists($file)) {
                    $opts[CURLOPT_INFILESIZE] = filesize($file);
                    $fp = @fopen($file, 'r');
                    if ($fp) {
                        $opts[CURLOPT_INFILE] = $fp;
                    } else {
                        $e = error_get_last();
                        throw new WebRequestHandlerCurlException("Failed to open curl input file: " . $e['message'], 0, $e['type'], $e['file'],
                            $e['line']);
                    }
                } else {
                    throw new WebRequestHandlerCurlException("File {$file} doesn't exist");
                }
            } else {
                $opts[CURLOPT_HTTPGET] = true;
                if (!empty($parameters)) {
                    $opts[CURLOPT_URL] .= '?' . http_build_query($parameters);
                }
            }
        } catch (Exception $ex) {
            throw new WebRequestHandlerCurlException("Failure occurred setting request parameters", 0, 1, __FILE__, __LINE__, $ex);
        }

        return $this->_transmit($opts, $output_file);
    }

    /**
     * Method prepares settings for a PUT request.
     *
     * @param string $res URI resouce.
     * @param ?WebRequestHeaders $headers Web request headers to be sent
     * @param mixed $parameters Parameters passed to the get call can be CurlFile, Array, or other.
     * @param string $output_file Used when storing output to a file
     * @param WebRequestMode $mode WebRequestMode::DATA or WebRequestMode::FORM
     *
     * @return WebRequestResponse
     *
     * @throws WebRequestHandlerCurlException
     */
    public function put(string $res, ?WebRequestHeaders $headers, mixed $parameters, string $output_file, WebRequestMode $mode): WebRequestResponse
    {
        // Process default options
        $opts = $this->create_context($res);

        // Append additional headers as needed
        if ($headers != null) {
            foreach ($headers as $k => $v) {
                $opts[CURLOPT_HTTPHEADER][] = "$k: $v";
            }
        }

        try {
            if ($mode == WebRequestMode::DATA) {
                $opts[CURLOPT_PUT] = true;
                // Put in data mode must be CURLFile type since we will send a file as the output
                if (!is_object($parameters) || strcmp('CURLFile', get_class($parameters)) != 0) {
                    throw new WebRequestHandlerCurlException("Invalid arguments {$opts[CURLOPT_CUSTOMREQUEST]} WebRequestMode::DATA requires CURLFile type");
                }
                $file = $parameters->getFilename();
                if (file_exists($file)) {
                    $opts[CURLOPT_INFILESIZE] = filesize($file);
                    $fp = @fopen($file, 'r');
                    if ($fp) {
                        $opts[CURLOPT_INFILE] = $fp;
                    } else {
                        $e = error_get_last();
                        throw new WebRequestHandlerCurlException("Failed to open curl input file: " . $e['message'], 0, $e['type'], $e['file'],
                            $e['line']);
                    }
                } else {
                    throw new WebRequestHandlerCurlException("File {$file} doesn't exist");
                }
            } else {
                // Form data post...
                $opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
                $opts[CURLOPT_POSTFIELDS] = $parameters;
            }
        } catch (Exception $ex) {
            throw new WebRequestHandlerCurlException("Failure occurred setting request parameters", 0, 1, __FILE__, __LINE__, $ex);
        }
        return $this->_transmit($opts, $output_file);
    }

    /**
     * Method prepares settings for a POST request.
     *
     * @param string $res URI resouce.
     * @param ?WebRequestHeaders $headers Web request headers to be sent
     * @param mixed $parameters Parameters passed to the get call can be CurlFile, Array, or other.
     * @param string $output_file Used when storing output to a file
     * @param WebRequestMode $mode WebRequestMode::DATA or WebRequestMode::FORM
     *
     * @return WebRequestResponse
     *
     * @throws WebRequestHandlerCurlException
     */
    public function post(string $res, ?WebRequestHeaders $headers, mixed $parameters, string $output_file, WebRequestMode $mode): WebRequestResponse
    {
        // Process default options
        $opts = $this->create_context($res);

        // Append additional headers as needed
        if ($headers != null) {
            foreach ($headers as $k => $v) {
                $opts[CURLOPT_HTTPHEADER][] = "$k: $v";
            }
        }

        try {
            $opts[CURLOPT_POST] = true;
            if ($mode == WebRequestMode::DATA) {
                // Put in data mode must be CURLFile type since we will send a file as the output
                if (!is_object($parameters) || strcmp('CURLFile', get_class($parameters)) != 0) {
                    throw new WebRequestHandlerCurlException("Invalid arguments {$opts[CURLOPT_CUSTOMREQUEST]} WebRequestMode::DATA requires CURLFile type");
                }
                $file = $parameters->getFilename();
                if (file_exists($file)) {
                    $opts[CURLOPT_INFILESIZE] = filesize($file);
                    $fp = @fopen($file, 'r');
                    if ($fp) {
                        $opts[CURLOPT_INFILE] = $fp;
                    } else {
                        $e = error_get_last();
                        throw new WebRequestHandlerCurlException("Failed to open curl input file: " . $e['message'], 0, $e['type'], $e['file'],
                            $e['line']);
                    }
                } else {
                    throw new WebRequestHandlerCurlException("File {$file} doesn't exist");
                }
            } else {
                // Form data post...
                $opts[CURLOPT_POSTFIELDS] = $parameters;
            }
        } catch (Exception $ex) {
            throw new WebRequestHandlerCurlException("Failure occurred setting request parameters", 0, 1, __FILE__, __LINE__, $ex);
        }
        return $this->_transmit($opts, $output_file);
    }

    /**
     * Method prepares settings for a DELETE request.
     *
     * @param string $res URI resouce.
     * @param ?WebRequestHeaders $headers Web request headers to be sent
     * @param mixed $parameters Parameters passed to the get call can be CurlFile, Array, or other.
     * @param string $output_file Used when storing output to a file
     * @param WebRequestMode $mode WebRequestMode::DATA or WebRequestMode::FORM
     *
     * @return WebRequestResponse
     *
     * @throws WebRequestHandlerCurlException
     */
    public function delete(string $res, ?WebRequestHeaders $headers, mixed $parameters, string $output_file, WebRequestMode $mode): WebRequestResponse
    {
        // Process default options
        $opts = $this->create_context($res);

        // Append additional headers as needed
        foreach ($headers as $k => $v) {
            $opts[CURLOPT_HTTPHEADER][] = "$k: $v";
        }
        try {
            $opts[CURLOPT_CUSTOMREQUEST] = "DELETE";
            if ($mode == WebRequestMode::DATA) {
                $opts[CURLOPT_POST] = true;
                // Put in data mode must be CURLFile type since we will send a file as the output
                if (!is_object($parameters) || strcmp('CURLFile', get_class($parameters)) != 0) {
                    throw new WebRequestHandlerCurlException("Invalid arguments {$opts[CURLOPT_CUSTOMREQUEST]} WebRequestMode::DATA requires CURLFile type");
                }
                $file = $parameters->getFilename();
                if (file_exists($file)) {
                    $opts[CURLOPT_INFILESIZE] = filesize($file);
                    $fp = @fopen($file, 'r');
                    if ($fp) {
                        $opts[CURLOPT_INFILE] = $fp;
                    } else {
                        $e = error_get_last();
                        throw new WebRequestHandlerCurlException("Failed to open curl input file: " . $e['message'], 0, $e['type'], $e['file'],
                            $e['line']);
                    }
                } else {
                    throw new WebRequestHandlerCurlException("File {$file} doesn't exist");
                }
            } else {
                $opts[CURLOPT_POSTFIELDS] = $parameters;
            }
        } catch (Exception $ex) {
            throw new WebRequestHandlerCurlException("Failure occurred setting request parameters", 0, 1, __FILE__, __LINE__, $ex);
        }
        return $this->_transmit($opts, $output_file);
    }
}