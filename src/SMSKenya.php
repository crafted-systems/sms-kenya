<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 3/13/18
 * Time: 12:09 AM
 */

namespace CraftedSystems\SMSKenya;

class SMSKenya
{
    /**
     * Base URL.
     *
     * @var string
     */
    const BASE_URL = 'smskenya.net';

    /**
     * settings .
     *
     * @var array.
     */
    protected $settings;

    /**
     * MicroMobileSMS constructor.
     * @param $settings
     * @throws \Exception
     */
    public function __construct($settings)
    {
        $this->settings = (object)$settings;

        if (empty($this->settings->api_key)) {
            throw new \Exception('Please ensure that API Key for SMSKenya has been set.');
        }
    }

    /**
     * @param $recipient
     * @param $message
     * @return mixed
     * @throws \Exception
     */
    public function send($recipient, $message)
    {
        if (!is_string($message)) {

            throw new \Exception('The Message Should be a string');
        }

        if (!is_string($recipient)) {
            throw new \Exception('The Phone number should be a string');
        }

        $fp = fsockopen("ssl://" . self::BASE_URL, 443, $errno, $errstr, 30);

        $post_data = array(
            array('msisdn' => $recipient, 'message' => $message)
        );

        $jsondata = base64_encode(json_encode($post_data));
        $content = http_build_query(array('data' => $jsondata));
        fwrite($fp, "POST /api/apiphp HTTP/1.1\r\n");
        fwrite($fp, "Host: " . self::BASE_URL . " \r\n");
        fwrite($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
        fwrite($fp, "Content-Length: " . strlen($content) . "\r\n");
        fwrite($fp, "Content-token: " . $this->settings->api_key . "\r\n");
        fwrite($fp, "Connection: close\r\n");
        fwrite($fp, "\r\n");
        fwrite($fp, $content);
        header('Content-type: text/plain');

        $responsedata = null;

        while (!feof($fp)) {
            $response = fgets($fp, 1024);
            if (strrpos($response, "response")) {
                $responsedata = json_decode($response);
            }
        }

        return $responsedata;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function getDeliveryReports(\Illuminate\Http\Request $request)
    {
        return json_decode($request->getContent());
    }

    /**
     * @return float
     */
    public function getBalance()
    {
        return 100;

    }

}