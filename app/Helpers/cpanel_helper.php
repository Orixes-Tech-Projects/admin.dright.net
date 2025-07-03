<?php

use CodeIgniter\HTTP\CURLRequest;

if (!function_exists('addSubdomain')) {
    if (!function_exists('create_subdomain_cpanel')) {

        function create_subdomain_cpanel($subdomain, $rootDomain, $dir)
        {
            $cpanelUser = 'dright';
            $apiToken = '43L85J0NONQNFZGR7X8B2IKV2TZX41PJ';
            $cpanelHost = 'dright.net';

            $url = "https://$cpanelHost:2083/json-api/cpanel";

            $params = array(
                'cpanel_jsonapi_user' => $cpanelUser,
                'cpanel_jsonapi_apiversion' => 2,
                'cpanel_jsonapi_module' => 'SubDomain',
                'cpanel_jsonapi_func' => 'addsubdomain',
                'domain' => $subdomain,
                'rootdomain' => $rootDomain,
                'dir' => $dir
            );

            $headers = array(
                'Authorization' => "cpanel $cpanelUser:$apiToken"
            );

            try {
                $client = \Config\Services::curlrequest();
                $client->request('GET', $url, array(
                    'headers' => $headers,
                    'query' => $params,
                    'verify' => false
                ));
            } catch (\Throwable $e) {
                log_message('error', 'Subdomain creation failed: ' . $e->getMessage());
            }
        }
    }
}


if (!function_exists('create_postgres_db_with_existing_user')) {
    function create_postgres_db_with_existing_user($dbName, $existingUser)
    {
        $cpanelUser = 'dright';
        $apiToken = '43L85J0NONQNFZGR7X8B2IKV2TZX41PJ';
        $server = 'dright.net';

        $headers = array(
            'Authorization' => "cpanel $cpanelUser:$apiToken"
        );
        $client = \Config\Services::curlrequest();
        try {

            $createDbResponse = $client->request('POST', "https://$server:2083/execute/Postgresql/create_database", array(
                'headers' => $headers,
                'form_params' => array('name' => $dbName),
                'verify' => false,
            ));

            $grantResponse = $client->request('POST', "https://$server:2083/execute/Postgresql/grant_all_privileges", array(
                'headers' => $headers,
                'form_params' => array(
                    'user' => $existingUser,
                    'database' => $dbName
                ),
                'verify' => false,
            ));

        } catch (\Exception $e) {
            log_message('error', 'PostgreSQL creation error: ' . $e->getMessage());
            return false;
        }
    }
}




