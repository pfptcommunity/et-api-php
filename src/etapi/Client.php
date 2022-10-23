<?php
/**
 * This code was tested against PHP version 8.1.2
 *
 * @author Ludvik Jerabek
 * @package et-api-php
 * @version 1.0.0
 * @license MIT
 */


namespace etapi;

use ErrorException;
use Exception;
use etapi\http\WebProtocolVersion;
use etapi\http\WebRequestHeaders;
use etapi\rest\ClientResource;
use etapi\http\WebRequestHandlerOptions;
use etapi\http\WebRequestResponse;
use etapi\rest\Sample;
use etapi\rest\Sid;
use etapi\rest\Ip;
use etapi\rest\Domain;

/**
 * Client exception class is thrown by Client
 */
class ClientException extends ErrorException
{
}

/**
 * Class is the primary instance of an ClientResource which provides the base for all rest and services
 *
 * @link http://apidocs.emergingthreats.net/ Emerging Threats API Guide
 */
class Client extends ClientResource
{
    /**
     * User Agent Shown in HTTP header
     * @var string constant
     */
    const USER_AGENT = 'Proofpoint-PHP/1.0';
    /**
     * Base URI to the webservice eg. fe_cms_device.mydomain.com
     *
     * @var string api version
     */
    protected string $api_version;
    /**
     * Supported versions of the API, prevent api load if not in supported array
     * @access protected
     *
     * @var array of supported versions
     */
    protected static array $versions;

    /**
     * Web services API client constructor
     *
     * @param string $base_uri
     * @param string $version
     * @param string $et_api_token
     *
     * @throws ClientException
     *
     */
    public function __construct(string $base_uri, string $version, string $et_api_token)
    {
        // Configure supported versions currently 1
        self::$versions = array('v1');

        // Check to be sure our version is supported always get latest
        $this->api_version = in_array($version, self::$versions) ? $version : end(self::$versions);

        try {
            $headers = new WebRequestHeaders();
            $headers->set('Accept-Charset', 'utf-8')
                ->set('Authorization', trim($et_api_token));
            $options = new WebRequestHandlerOptions();
            $options->set_user_agent(self::USER_AGENT)
                ->set_protocol_version(WebProtocolVersion::HTTP_VERSION_1_1)
                ->set_ssl_ca_file(dirname(__FILE__) . '/cacert.pem')
                ->set_ssl_verify(false)
                ->set_follow_location(true)
                ->set_headers($headers);
            parent::__construct($base_uri, "/$this->api_version", $options);
        } catch (Exception $ex) {
            throw new ClientException("Failed to create Emerging Threats API client", 0, 1, __FILE__, __LINE__, $ex);
        }
    }

    public function repcategories()
    {
        return $this->get_client()->retrieve($this->get_uri() . '/repcategories');
    }

    public function ip(string $ip): Ip
    {
        return new Ip($this->get_client(), $this->get_uri() . "/ips/$ip");
    }

    public function domain(string $domain): Domain
    {
        return new Domain($this->get_client(), $this->get_uri() . "/domains/$domain");
    }

    public function sample(string $md5): Sample
    {
        return new Sample($this->get_client(), $this->get_uri() . "/samples/$md5");
    }

    public function sid(string $sid): Sid
    {
        return new Sid($this->get_client(), $this->get_uri() . "/sids/$sid");
    }

    /**
     * Method is required by the ClientResource base class.
     *
     * @param WebRequestResponse $response Web response request.
     *
     * @return boolean
     *
     * @throws ClientException
     *
     */
    protected function process_crud_response(WebRequestResponse $response): mixed
    {
        // Well known ET API Errors
        switch ($response->info->get_http_code()) {
            case 401:
                throw new ClientException("Unauthorized – You did not provide an API key? ({$response->message} {$response->info->get_http_code()})",
                    $response->info->get_http_code());
                break;
            case 403:
                throw new ClientException("Forbidden – The API key does not have access to the requested action or your subscription has elapsed ({$response->message} {$response->info->get_http_code()})",
                    $response->info->get_http_code());
                break;
            case 404:
                throw new ClientException("Not Found – The requested action does not exist ({$response->message} {$response->info->get_http_code()})",
                    $response->info->get_http_code());
                break;
            case 408:
                throw new ClientException("Request Timeout – The request took too long to complete on our side. Please reduce the amount of information you are requesting, or try again later ({$response->message} {$response->info->get_http_code()})",
                    $response->info->get_http_code());
                break;
            case 429:
                throw new ClientException("Rate Limit Exceeded – You have exceeded your provisioned rate limit. If this becomes a regular occurrence, please contact sales to have your rate limit increased ({$response->message} {$response->info->get_http_code()})",
                    $response->info->get_http_code());
                break;
            case 500:
                throw new ClientException("Internal Server Error – We had a problem internal to our systems. Please try again later ({$response->message} {$response->info->get_http_code()})",
                    $response->info->get_http_code());
                break;
        }
        // Some form of 200 range error code
        if (200 <= $response->info->get_http_code() && $response->info->get_http_code() < 300) {
            $data = json_decode($response->body, true);
            if ($data === null) {
                throw new ClientException("Failed to process HTTP response data ({$response->message} {$response->info->get_http_code()})",
                    $response->info->get_http_code());
            }
            return $data;
        }
        throw new ClientException("Failed to process HTTP response ({$response->info->get_http_code()})", $response->info->get_http_code());
    }
}