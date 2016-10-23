<?php
namespace XjtuApi;

use GuzzleHttp\Client;

class XjtuApi
{
    protected $client = null;

    public function __construct()
    {
        $this->client = new Client([
            'cookies' => true,
            'verify' => false,
        ]);
    }

    public static function responseError($msg = 'error', $code = -1)
    {
        return self::response($code, $msg);
    }

    public static function response($code = -1, $msg = 'error', $result = null)
    {
        return [
            'code' => $code,
            'msg' => $msg,
            'result' => $result,
        ];
    }

    public static function responseOk($result = true, $msg = 'ok', $code = 0)
    {
        return self::response($code, $msg, $result);
    }

    public static function find($haystack, $needle)
    {
        return (false !== strpos($haystack, $needle));
    }

    public static function &match($pattern, $subject)
    {
        preg_match($pattern, $subject, $matches);
        return $matches;
    }

    public static function &match_all($pattern, $subject)
    {
        preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER);
        return $matches;
    }

    public function request($method, $uri = '', array $options = [])
    {
        try {
            $response = $this->client->request($method, $uri, $options);
        } catch (\Exception $e) {
            return false;
        }
        return $response->getBody()->getContents();
    }
}
