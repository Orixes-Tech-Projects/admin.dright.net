<?php

namespace App\Models;

use CodeIgniter\Model;

class CpanelDomains extends Model
{
    protected $cpanelUser;
    protected $cpanelPass;
    protected $cpanelHost;

    public function __construct()
    {
        parent::__construct();

        $this->cpanelUser = 'dright'; // Consider using getenv() or config file
        $this->cpanelPass = 'OSa.7TrAZ!32';
        $this->cpanelHost = 'dright.net';
    }

    public function CreateSubDomain($Details = [])
    {
        if (empty($Details['SubDomain'])) {
            return;
        }

        $SaveResponse = [
            'ProfileID' => $Details['ProfileID'],
            'ProductType' => $Details['ProductType'],
            'Product' => $Details['Product'],
            'Domain' => $Details['SubDomain'],
            'Status' => 'fail',
            'Message' => ''
        ];

        $subdomain = strtok($Details['SubDomain'], '.');

        $apiResponse = $this->MakeCpanelApiRequest(
            $subdomain,
            $Details['RootDomain'],
            $Details['Directory']
        );

        if ($apiResponse['httpCode'] === 200) {
            $result = $apiResponse['data'];

            if ($this->IsSuccessResponse($result)) {
                $SaveResponse['Status'] = 'success';
                $SaveResponse['Message'] = 'Subdomain created successfully';
            } else {
                $errorMessage = $this->ExtractErrorMessage($result);
                $formattedError = $this->FormatError($errorMessage);
                $SaveResponse['Message'] = $formattedError['msg'];
            }
        } else {
            $SaveResponse['Message'] = "Failed to create subdomain. HTTP Error: {$apiResponse['httpCode']}";
        }
        $this->AddSubDomainCreationStatusInRecord($SaveResponse);
    }

    private function MakeCpanelApiRequest($subdomain, $rootDomain, $dir)
    {
        $url = "https://{$this->cpanelHost}:2083/json-api/cpanel";
        $params = [
            'cpanel_jsonapi_module' => 'SubDomain',
            'cpanel_jsonapi_func' => 'addsubdomain',
            'cpanel_jsonapi_version' => 2,
            'domain' => $subdomain,
            'rootdomain' => $rootDomain,
            'dir' => $dir
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->cpanelUser}:{$this->cpanelPass}");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'httpCode' => $httpCode,
            'data' => json_decode($response, true)
        ];
    }

    private function IsSuccessResponse($result)
    {
        if (isset($result['data'][0]['result'])) {
            return $result['data'][0]['result'] == 1;
        }

        if (isset($result['cpanelresult']['data'][0]['result'])) {
            return $result['cpanelresult']['data'][0]['result'] == 1;
        }

        return false;
    }

    private function ExtractErrorMessage($result)
    {
        if (isset($result['data'][0]['reason'])) {
            return $result['data'][0]['reason'];
        }

        if (isset($result['cpanelresult']['data'][0]['reason'])) {
            return $result['cpanelresult']['data'][0]['reason'];
        }

        if (isset($result['error'])) {
            return $result['error'];
        }

        if (isset($result['cpanelresult']['error'])) {
            return $result['cpanelresult']['error'];
        }

        return 'Unknown error';
    }

    private function FormatError($errorMessage)
    {
        $errorMessage = preg_replace('/\(XID \w+\)\s*/', '', $errorMessage);
        $errorMessage = explode(' at ', $errorMessage)[0];
        return ['status' => 'fail', 'msg' => "Failed to create subdomain. Error: {$errorMessage}"];
    }

    private function AddSubDomainCreationStatusInRecord($Response = [])
    {
        $Crud = new Crud();
        $Crud->AddRecord('profiles_auto_domains_status', $Response);
    }
}