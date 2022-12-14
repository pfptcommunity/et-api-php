# Emerging Threats API PHP Library

Library implements all of the functions of the Emerging Threats API via PHP.

### Requirements:

* PHP 8.1
* php-curl
* php-openssl

### Getting Started

```php
require '../src/api.php';

$et_api_key = "<enter_et_api_key_here>";
$et_api_base_uri = "https://api.emergingthreats.net";
$et_api_version = "v1";
$api_client = null;

try {
    $api_client = new etapi\Client($et_api_base_uri, $et_api_version,$et_api_key);
} catch (Exception $e) {
    error_log('Failed to create ET API client: ' . $e->getMessage());
    while( ($e = $e->getPrevious()) != NULL ) {
        error_log('Failed to create ET API client: ' . $e->getMessage());
    }
    exit(1);
}
```

### Querying Reputation Metadata

```php
try {

    $reputation_categories = $api_client->repcategories();
    printf("Success: [%s]\n", $reputation_categories['success'] ? "True" : "False");
    foreach( $reputation_categories['response'] as $item )
    {
        printf( "Name: %s\n", $item['name']);
        printf( "Description: %s\n", $item['description']);
        printf( "----------------------------------------------------------------------------------------\n");
    }

} catch (Exception $e) {
    error_log('Failed to query ET API client: ' . $e->getMessage());
    error_log('Failed to query ET API client: ' . $e->getPrevious()->getMessage());
    exit(1);
}
```

For more information on reputation metadata please see http://apidocs.emergingthreats.net/#reputation-metadata

### Querying Domain Information

```php
try {
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
    
} catch (Exception $e) {
    error_log('Failed to query ET API client: ' . $e->getMessage());
    error_log('Failed to query ET API client: ' . $e->getPrevious()->getMessage());
    exit(1);
}
```

For more information on domain information please see http://apidocs.emergingthreats.net/#domain-information

### Querying IP Information

```php
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
    
} catch (Exception $e) {
    error_log('Failed to query ET API client: ' . $e->getMessage());
    error_log('Failed to query ET API client: ' . $e->getPrevious()->getMessage());
    exit(1);
}
```

For more information on IP information please see http://apidocs.emergingthreats.net/#ip-information

### Querying Malmare Samples

```php
try {
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
    
} catch (Exception $e) {
    error_log('Failed to query ET API client: ' . $e->getMessage());
    error_log('Failed to query ET API client: ' . $e->getPrevious()->getMessage());
    exit(1);
}
```

For more information on malware samples please see http://apidocs.emergingthreats.net/#malware-samples

### Querying Signature Information

```php
try {
    
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
```

For more information on signature information please see http://apidocs.emergingthreats.net/#signature-information

### Limitations

The API librarly will not re-query if the REST API returns an error. It is up to the implementer to catch 429 errors and
retry due to rate limits. The following exceptons can occur when a web service call fails due to rate limits:

401 Unauthorized - You did not provide an API key.  
403 Forbidden - The API key does not have access to the requested action or your subscription has elapsed.  
404 Not Found - The requested action does not exist.  
408 Request Timeout - The request took too long to complete on our side.  
429 Rate Limit Exceeded - You have exceeded your provisioned rate limit.  
500 Internal Server Error - We had a problem internal to our systems.

It's possible to determine the error code in the exception handler.

```php
{ 
    // Some API call here... 
    $sid_documentation = $api_client->sid($sid)->documentation();
    printf("Success Documentation: [%s]\n", $sid_documentation['success'] ? "True" : "False");
    var_dump($sid_documentation);
    
} catch (Exception $e) {
    error_log('Failed to query ET API client: ' . $e->getMessage());
    // This is the HTTP code returned by the web serice
    error_log('HTTP Request returned: ' . $e->getCode());
    error_log('Failed to query ET API client: ' . $e->getPrevious()->getMessage());
}
```

The above exception would return the following output:

```
Failed to query ET API client: Webservice CRUD retrieve call failed
HTTP Request returned: 429
Failed to query ET API client: Rate Limit Exceeded – You have exceeded your provisioned rate limit. If this becomes a regular occurrence, please contact sales to have your rate limit increased (Too Many Requests 429)
```

For more information on errors please see http://apidocs.emergingthreats.net/#errors
