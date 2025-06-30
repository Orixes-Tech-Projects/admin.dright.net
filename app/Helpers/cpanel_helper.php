<?php
if (!function_exists('addSubdomain')) {

    if (!function_exists('create_subdomain_cpanel')) {

        function create_subdomain_cpanel(string $subdomain, string $rootDomain, string $dir): void
        {
            $cpanelUser = 'dright';
            $apiToken = '43L85J0NONQNFZGR7X8B2IKV2TZX41PJ';
            $cpanelHost = 'dright.net';

            $url = "https://$cpanelHost:2083/json-api/cpanel";

            $params = [
                'cpanel_jsonapi_user' => $cpanelUser,
                'cpanel_jsonapi_apiversion' => 2,
                'cpanel_jsonapi_module' => 'SubDomain',
                'cpanel_jsonapi_func' => 'addsubdomain',
                'domain' => $subdomain,
                'rootdomain' => $rootDomain,
                'dir' => $dir
            ];

            $headers = [
                'Authorization' => "cpanel $cpanelUser:$apiToken"
            ];

            try {
                $client = \Config\Services::curlrequest();
                $client->request('GET', $url, [
                    'headers' => $headers,
                    'query' => $params,
                    'verify' => false
                ]);
            } catch (\Throwable $e) {
                log_message('error', 'Subdomain creation failed: ' . $e->getMessage());
            }
        }
    }


}


