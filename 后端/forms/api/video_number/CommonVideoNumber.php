<?php

namespace app\forms\api\video_number;

use app\models\Model;
use GuzzleHttp\Client;

class CommonVideoNumber extends Model
{
    public static function post($url, $body = array())
    {
        $response = self::getClient()->post($url, [
            'body' => json_encode($body, JSON_UNESCAPED_UNICODE),
        ]);

        return $response;
    }

    public static function postFile($url, $body = array())
    {
        $response = self::getClient()->request('POST', $url, [
            'multipart' => $body,
        ]);

        return $response;
    }

    public static function get($url, $body = array())
    {
        $response = self::getClient()->get(self::buildParams($url, $body));

        return $response;
    }

    private static function buildParams($url, $array)
    {
        $query = http_build_query($array);
        $url = trim($url, '?');
        $url = trim($url, '&');
        if (mb_stripos($url, '?')) {
            return $url . '&' . $query;
        } else {
            return $url . '?' . $query;
        }
    }

    private static function getClient()
    {
        return new Client([
            'verify' => false,
            'Content-Type' => 'application/json; charset=UTF-8',
        ]);
    }
}
