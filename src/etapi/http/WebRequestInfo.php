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

class WebRequestInfo
{
    public readonly array $info;

    public function __construct(CurlHandle $curl)
    {
        $this->info = curl_getinfo($curl);
    }

    public function get_url(): ?string
    {
        return (array_key_exists("url", $this->info)) ? $this->info["url"] : null;
    }

    public function get_content_type(): ?string
    {
        return (array_key_exists("content_type", $this->info)) ? $this->info["content_type"] : null;
    }

    public function get_http_code(): ?int
    {
        return (array_key_exists("http_code", $this->info)) ? $this->info["http_code"] : null;
    }

    public function get_header_size(): ?int
    {
        return (array_key_exists("header_size", $this->info)) ? $this->info["header_size"] : null;
    }

    public function get_request_size()
    {
        return (array_key_exists("request_size", $this->info)) ? $this->info["request_size"] : null;
    }

    public function get_filetime()
    {
        return (array_key_exists("filetime", $this->info)) ? $this->info["filetime"] : null;
    }

    public function get_ssl_verify_result()
    {
        return (array_key_exists("ssl_verify_result", $this->info)) ? $this->info["ssl_verify_result"] : null;
    }

    public function get_redirect_count()
    {
        return (array_key_exists("redirect_count", $this->info)) ? $this->info["redirect_count"] : null;
    }

    public function get_total_time()
    {
        return (array_key_exists("total_time", $this->info)) ? $this->info["total_time"] : null;
    }

    public function get_namelookup_time()
    {
        return (array_key_exists("namelookup_time", $this->info)) ? $this->info["namelookup_time"] : null;
    }

    public function get_connect_time()
    {
        return (array_key_exists("connect_time", $this->info)) ? $this->info["connect_time"] : null;
    }

    public function get_pretransfer_time()
    {
        return (array_key_exists("pretransfer_time", $this->info)) ? $this->info["pretransfer_time"] : null;
    }

    public function get_size_upload()
    {
        return (array_key_exists("size_upload", $this->info)) ? $this->info["size_upload"] : null;
    }

    public function get_size_download()
    {
        return (array_key_exists("size_download", $this->info)) ? $this->info["size_download"] : null;
    }

    public function get_speed_download()
    {
        return (array_key_exists("speed_download", $this->info)) ? $this->info["speed_download"] : null;
    }

    public function get_speed_upload()
    {
        return (array_key_exists("speed_upload", $this->info)) ? $this->info["speed_upload"] : null;
    }

    public function get_download_content_length()
    {
        return (array_key_exists("download_content_length", $this->info)) ? $this->info["download_content_length"] : null;
    }

    public function get_upload_content_length()
    {
        return (array_key_exists("upload_content_length", $this->info)) ? $this->info["upload_content_length"] : null;
    }

    public function get_starttransfer_time()
    {
        return (array_key_exists("starttransfer_time", $this->info)) ? $this->info["starttransfer_time"] : null;
    }

    public function get_redirect_time()
    {
        return (array_key_exists("redirect_time", $this->info)) ? $this->info["redirect_time"] : null;
    }

    public function get_certinfo()
    {
        return (array_key_exists("certinfo", $this->info)) ? $this->info["certinfo"] : null;
    }

    public function get_primary_ip()
    {
        return (array_key_exists("primary_ip", $this->info)) ? $this->info["primary_ip"] : null;
    }

    public function get_primary_port()
    {
        return (array_key_exists("primary_port", $this->info)) ? $this->info["primary_port"] : null;
    }

    public function get_local_ip()
    {
        return (array_key_exists("local_ip", $this->info)) ? $this->info["local_ip"] : null;
    }

    public function get_local_port()
    {
        return (array_key_exists("local_port", $this->info)) ? $this->info["local_port"] : null;
    }

    public function get_redirect_url()
    {
        return (array_key_exists("redirect_url", $this->info)) ? $this->info["redirect_url"] : null;
    }

    public function get_request_header()
    {
        return (array_key_exists("request_header ", $this->info)) ? $this->info["request_header "] : null;
    }
}