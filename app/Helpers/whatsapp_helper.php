<?php

if (!function_exists('getWhatsAppAdminNumbers')) {
    /**
     * Returns static array of admin phone numbers who receive BCC of each WhatsApp message
     * Set WHATSAPP_ADMIN_NUMBERS in .env (comma-separated) to override
     */
    function getWhatsAppAdminNumbers()
    {
        static $whatsappAdminNumbers = null;
        if ($whatsappAdminNumbers === null) {
            $whatsappAdminNumbers = WHATSAPP_ADMIN_NUMBERS;
            $whatsappAdminNumbers = explode(',', $whatsappAdminNumbers);
            $whatsappAdminNumbers = array_map('trim', $whatsappAdminNumbers);
        }
        return $whatsappAdminNumbers;
    }
}

if (!function_exists('SendWhatsAPP')) {
    /**
     * Send a WhatsApp message via Green API
     *
     * @param string $to      Recipient number (e.g. 923455913609 or 923455913609@c.us for private, @g.us for group)
     * @param string $message Message text to send
     * @param array  $options Optional: linkPreview, typePreview (large|small|none)
     * @return string|false  API response as JSON string, or false on failure
     */
    function SendWhatsAPP($to, $message, $options = [])
    {
        $idInstance      = env('GREENAPI_ID_INSTANCE', '7105481336');
        $apiTokenInstance = env('GREENAPI_TOKEN', 'f088eb45574b4d96a8f9dddc72daea8f500374a99f9141cfb1');
        $host            = env('GREENAPI_HOST', '7105.api.greenapi.com');

        $url = sprintf(
            'https://%s/waInstance%s/sendMessage/%s',
            $host,
            $idInstance,
            $apiTokenInstance
        );

        // Format chatId: append @c.us if not already present (private chat)
        $chatId = $to;
        if (strpos($to, '@c.us') === false && strpos($to, '@g.us') === false) {
            $chatId = preg_replace('/[^0-9]/', '', $to) . '@c.us';
        }

        $data = [
            'chatId'      => $chatId,
            'message'     => $message,
            'linkPreview' => $options['linkPreview'] ?? true,
            'typePreview' => $options['typePreview'] ?? 'large',
        ];

        $opts = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
                'timeout' => 30,
            ],
        ];

        $context = stream_context_create($opts);
        $response = @file_get_contents($url, false, $context);

        return $response !== false ? $response : false;
    }
}

if (!function_exists('SendWhatsAPPWithBCC')) {
    /**
     * Send a WhatsApp message to recipient and BCC copy to all admin numbers
     *
     * @param string $to      Recipient number
     * @param string $message Message text to send
     * @param array  $options Optional: linkPreview, typePreview
     * @return array Main response and BCC results: ['main' => response, 'bcc' => [response, ...]]
     */
    function SendWhatsAPPWithBCC($to, $message, $options = [])
    {
        $results = ['main' => SendWhatsAPP($to, $message, $options), 'bcc' => []];

        foreach (getWhatsAppAdminNumbers() as $adminNumber) {
            $adminNumber = trim($adminNumber);
            if (!empty($adminNumber)) {
                $results['bcc'][] = SendWhatsAPP($adminNumber, $message, $options);
            }
        }

        return $results;
    }
}
