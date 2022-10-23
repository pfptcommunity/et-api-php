<?php
/**
 * This code was tested against PHP version 8.1.2
 *
 * @author Ludvik Jerabek
 * @package et-api-php
 * @version 1.0.0
 * @license MIT
 */

require '../src/etapi.php';

$et_api_key = "<enter_et_api_key_here>";
$et_api_base_uri = "https://api.emergingthreats.net";
$et_api_version = "v1";
$api_client = null;

try {
    $api_client = new etapi\Client($et_api_base_uri, $et_api_version, $et_api_key);
} catch (Exception $e) {
    error_log('Failed to create ET API client: ' . $e->getMessage());
    while (($e = $e->getPrevious()) != null) {
        error_log('Failed to create ET API client: ' . $e->getMessage());
    }
    exit(1);
}


try {
    $reputation_categories = $api_client->repcategories();
    printf("Success: [%s]\n", $reputation_categories['success'] ? "True" : "False");
    foreach ($reputation_categories['response'] as $item) {
        printf("Name: %s\n", $item['name']);
        printf("Description: %s\n", $item['description']);
        printf("----------------------------------------------------------------------------------------\n");
    }
} catch (Exception $e) {
    error_log('Failed to query ET API client: ' . $e->getMessage());
    error_log('Failed to query ET API client: ' . $e->getPrevious()->getMessage());
    exit(1);
}

try {
    $ip = "139.59.254.147";
    $ip_reputation = $api_client->ip($ip)->reputation();
    printf("Success: [%s]\n", $ip_reputation['success'] ? "True" : "False");
    var_dump($ip_reputation);

    $ip_geo_loc = $api_client->ip($ip)->geoloc();
    printf("Success: [%s]\n", $ip_geo_loc['success'] ? "True" : "False");
    var_dump($ip_geo_loc);

    $ip_domains = $api_client->ip($ip)->domains();
    printf("Success: [%s]\n", $ip_domains['success'] ? "True" : "False");
    var_dump($ip_domains);

    $ip_samples = $api_client->ip($ip)->samples();
    printf("Success: [%s]\n", $ip_samples['success'] ? "True" : "False");
    var_dump($ip_samples);

    $ip_urls = $api_client->ip($ip)->urls();
    printf("Success: [%s]\n", $ip_urls['success'] ? "True" : "False");
    var_dump($ip_urls);

    $ip_events = $api_client->ip($ip)->events();
    printf("Success: [%s]\n", $ip_events['success'] ? "True" : "False");
    var_dump($ip_events);

    // Domains Stuff

    $domain = "google.com";

    $domain_rep = $api_client->domain($domain)->reputation();
    printf("Success: [%s]\n", $domain_rep['success'] ? "True" : "False");
    var_dump($domain_rep);

    $domain_ips = $api_client->domain($domain)->ips();
    printf("Success: [%s]\n", $domain_ips['success'] ? "True" : "False");
    var_dump($domain_ips);

    $domain_samples = $api_client->domain($domain)->samples();
    printf("Success: [%s]\n", $domain_samples['success'] ? "True" : "False");
    var_dump($domain_samples);

    $domain_urls = $api_client->domain($domain)->urls();
    printf("Success: [%s]\n", $domain_urls['success'] ? "True" : "False");
    var_dump($domain_urls);

    $domain_events = $api_client->domain($domain)->events();
    printf("Success: [%s]\n", $domain_events['success'] ? "True" : "False");
    var_dump($domain_events);

    $domain_whois = $api_client->domain($domain)->whois();
    printf("Success: [%s]\n", $domain_whois['success'] ? "True" : "False");
    var_dump($domain_whois);

    $domain_ns = $api_client->domain($domain)->nameservers();
    printf("Success: [%s]\n", $domain_ns['success'] ? "True" : "False");
    var_dump($domain_ns);


    // Sample Data
    $md5 = "c6ab7cee9e5175a576270d2274711276";

    $sample_info = $api_client->sample($md5)();
    printf("Success: [%s]\n", $sample_info['success'] ? "True" : "False");
    var_dump($sample_info);

    $sample_conn = $api_client->sample($md5)->connections();
    printf("Success: [%s]\n", $sample_conn['success'] ? "True" : "False");
    var_dump($sample_conn);

    $sample_dns = $api_client->sample($md5)->dns();
    printf("Success: [%s]\n", $sample_dns['success'] ? "True" : "False");
    var_dump($sample_dns);

    $sample_events = $api_client->sample($md5)->events();
    printf("Success: [%s]\n", $sample_events['success'] ? "True" : "False");
    var_dump($sample_events);

    $sample_http = $api_client->sample($md5)->http();
    printf("Success: [%s]\n", $sample_http['success'] ? "True" : "False");
    var_dump($sample_http);

    // Signature Data
    $sid = "2022482";

    $sid_info = $api_client->sid($sid)();
    printf("Success: [%s]\n", $sid_info['success'] ? "True" : "False");
    var_dump($sid_info);

    $sid_ips = $api_client->sid($sid)->ips();
    printf("Success IPs: [%s]\n", $sid_ips['success'] ? "True" : "False");
    var_dump($sid_ips);

    $sid_domains = $api_client->sid($sid)->domains();
    printf("Success Domains: [%s]\n", $sid_domains['success'] ? "True" : "False");
    var_dump($sid_domains);

    $sid_samples = $api_client->sid($sid)->samples();
    printf("Success Samples: [%s]\n", $sid_samples['success'] ? "True" : "False");
    var_dump($sid_samples);

    $sid_text = $api_client->sid($sid)->text();
    printf("Success Text: [%s]\n", $sid_text['success'] ? "True" : "False");
    var_dump($sid_text);

    $sid_documentation = $api_client->sid($sid)->documentation();
    printf("Success Documentation: [%s]\n", $sid_documentation['success'] ? "True" : "False");
    var_dump($sid_documentation);

    $sid_references = $api_client->sid($sid)->references();
    printf("Success References: [%s]\n", $sid_references['success'] ? "True" : "False");
    var_export($sid_references);
} catch (Exception $e) {
    error_log('Failed to query ET API client: ' . $e->getMessage());
    error_log('Failed to query ET API client: ' . $e->getPrevious()->getMessage());
    exit(1);
}
