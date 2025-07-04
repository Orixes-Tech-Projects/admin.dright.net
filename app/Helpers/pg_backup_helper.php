<?php

if (!function_exists('pg_restore_database')) {

    function pg_restore_database($dbName, $backupFile, $options = array())
    {
        $result = pg_direct_restore($dbName, $backupFile, $options);
        if ($result['success']) {
            return $result;
        }

        $result = pg_cpanel_api_restore($dbName, $backupFile, $options);
        if ($result['success']) {
            return $result;
        }

        return pg_cpanel_uapi_restore($dbName, $backupFile, $options);
    }
}

if (!function_exists('pg_direct_restore')) {

    function pg_direct_restore($dbName, $backupFile, $options = array())
    {
        $defaultOptions = array(
            'pg_restore_path' => '/usr/bin/pg_restore',
            'psql_path' => '/usr/bin/psql',
            'host' => '127.0.0.1',
            'port' => '5432',
            'username' => 'dright_maindb',
            'password' => 'drightPostgrSQL',
            'cleanup' => false
        );
        $options = array_merge($defaultOptions, $options);

        if (!file_exists($backupFile)) {
            return array(
                'success' => false,
                'message' => "Backup file not found: {$backupFile}",
                'method' => 'direct'
            );
        }

        putenv("PGPASSWORD={$options['password']}");

        $fileExt = pathinfo($backupFile, PATHINFO_EXTENSION);
        $isCustomFormat = in_array($fileExt, array('backup', 'dump', 'pgdump'));

        try {
            if ($isCustomFormat) {
                $command = sprintf(
                    '%s -h %s -p %d -U %s -d %s -Fc %s',
                    escapeshellarg($options['pg_restore_path']),
                    escapeshellarg($options['host']),
                    $options['port'],
                    escapeshellarg($options['username']),
                    escapeshellarg($dbName),
                    escapeshellarg($backupFile)
                );
            } else {
                $command = sprintf(
                    '%s -h %s -p %d -U %s -d %s -f %s',
                    escapeshellarg($options['psql_path']),
                    escapeshellarg($options['host']),
                    $options['port'],
                    escapeshellarg($options['username']),
                    escapeshellarg($dbName),
                    escapeshellarg($backupFile)
                );
            }

            exec($command, $output, $returnCode);

            if ($returnCode === 0 && $options['cleanup']) {
                @unlink($backupFile);
            }

            return array(
                'success' => $returnCode === 0,
                'message' => $returnCode === 0 ? 'Direct restore successful' : implode("\n", $output),
                'output' => $output,
                'method' => 'direct',
                'command' => $command
            );
        } finally {
            putenv('PGPASSWORD');
        }
    }
}

if (!function_exists('pg_cpanel_api_restore')) {

    function pg_cpanel_api_restore($dbName, $backupFile, $options = array())
    {

        $defaultOptions = array(
            'cpanel_host' => 'dright.net',
            'cpanel_port' => '2083',
            'cpanel_user' => 'dright',
            'cpanel_password' => 'OSa.7TrAZ!32',
            'verify_ssl' => false
        );
        $options = array_merge($defaultOptions, $options);

        $url = "https://{$options['cpanel_host']}:{$options['cpanel_port']}/execute/Postgres/restore_database";

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . base64_encode($options['cpanel_user'] . ':' . $options['cpanel_password'])
            ),
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => array(
                'name' => $dbName,
                'backup' => $backupFile
            ),
            CURLOPT_SSL_VERIFYPEER => $options['verify_ssl'],
            CURLOPT_SSL_VERIFYHOST => $options['verify_ssl'] ? 2 : 0,
            CURLOPT_TIMEOUT => 300
        ));

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            return array(
                'success' => false,
                'message' => "cPanel API connection failed: {$error}",
                'method' => 'cpanel_api'
            );
        }

        $result = json_decode($response, true) ?? [];

        if ($httpCode !== 200 || isset($result['errors'])) {
            return [
                'success' => false,
                'message' => $result['errors'][0] ?? "HTTP {$httpCode} error",
                'response' => $result,
                'method' => 'cpanel_api'
            ];
        }

        return [
            'success' => true,
            'message' => $result['message'] ?? 'cPanel API restore successful',
            'response' => $result,
            'method' => 'cpanel_api'
        ];
    }
}

if (!function_exists('pg_cpanel_uapi_restore')) {

    function pg_cpanel_uapi_restore($dbName, $backupFile, array $options = []): array
    {
        $config = config('Database');
        $pgConfig = $config->default;

        $defaultOptions = [
            'cpanel_host' => $pgConfig['hostname'],
            'cpanel_port' => '2083',
            'cpanel_user' => $pgConfig['username'],
            'cpanel_password' => $pgConfig['password'],
            'verify_ssl' => false
        ];
        $options = array_merge($defaultOptions, $options);

        $url = "https://{$options['cpanel_host']}:{$options['cpanel_port']}/json-api/cpanel";

        $query = http_build_query([
            'cpanel_jsonapi_module' => 'Postgres',
            'cpanel_jsonapi_func' => 'restore',
            'cpanel_jsonapi_version' => 2,
            'name' => $dbName,
            'backup' => $backupFile
        ]);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url . '?' . $query,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . base64_encode($options['cpanel_user'] . ':' . $options['cpanel_password'])
            ],
            CURLOPT_SSL_VERIFYPEER => $options['verify_ssl'],
            CURLOPT_SSL_VERIFYHOST => $options['verify_ssl'] ? 2 : 0,
            CURLOPT_TIMEOUT => 300
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            return [
                'success' => false,
                'message' => "cPanel UAPI connection failed: {$error}",
                'method' => 'cpanel_uapi'
            ];
        }

        $result = json_decode($response, true) ?? [];

        if ($httpCode !== 200 || !empty($result['cpanelresult']['error'])) {
            return [
                'success' => false,
                'message' => $result['cpanelresult']['error'] ?? "HTTP {$httpCode} error",
                'response' => $result,
                'method' => 'cpanel_uapi'
            ];
        }

        return [
            'success' => true,
            'message' => $result['cpanelresult']['data']['message'] ?? 'cPanel UAPI restore successful',
            'response' => $result,
            'method' => 'cpanel_uapi'
        ];
    }
}