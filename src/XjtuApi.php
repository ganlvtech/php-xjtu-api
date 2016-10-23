<?php
namespace XjtuApi;

use GuzzleHttp\Client;

class XjtuApi implements \Serializable
{
    protected $client = null;

    public function __construct(array $config = [])
    {
        $defaults = [
            'cookies' => true,
            'verify' => false,
        ];
        $this->client = new Client($config + $defaults);
    }

    public static function response($code = -1, $msg = 'error', $result = null)
    {
        return [
            'code' => $code,
            'msg' => $msg,
            'result' => $result,
        ];
    }

    public static function responseError($msg = 'error', $code = -1)
    {
        return self::response($code, $msg);
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

    public function requestJsonDecode($method, $uri = '', array $options = [])
    {
        $content = $this->request($method, $uri, $options);
        return $content ? json_decode($content, true) : $content;
    }

    public function getConfig($option = null)
    {
        return $this->client->getConfig($option);
    }

    public function serialize()
    {
        $config = $this->getConfig();
        unset($config['handler']);
        return serialize($config);
    }

    public function unserialize($serialized)
    {
        self::__construct(unserialize($serialized));
    }
}
