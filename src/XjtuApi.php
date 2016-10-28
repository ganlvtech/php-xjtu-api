<?php
namespace XjtuApi;

use GuzzleHttp\Client;
use XjtuApi\Exception\MatchException;
use XjtuApi\Exception\RequestException;

class XjtuApi implements \Serializable
{
    const VERSION = '1.1.0';
    protected $client;

    public static function stringContains($haystack, $needle, $err_msg = '不包含目标字符串')
    {
        if (!self::find($haystack, $needle)) {
            throw new MatchException($err_msg);
        }
    }

    public static function find($haystack, $needle)
    {
        return (false !== strpos($haystack, $needle));
    }

    public static function stringNotContains($haystack, $needle, $err_msg = '包含了目标字符串')
    {
        if (self::find($haystack, $needle)) {
            throw new MatchException($err_msg);
        }
    }

    public static function &match($pattern, $subject, $err_msg = '正则匹配错误')
    {
        if (!self::preg_match($pattern, $subject, $matches)) {
            throw new MatchException($err_msg);
        }
        return $matches;
    }

    public static function preg_match($pattern, $subject, array &$matches = null)
    {
        return (1 === preg_match($pattern, $subject, $matches));
    }

    public static function &matchAll($pattern, $subject, $err_msg = '正则匹配错误')
    {
        if (!self::preg_match_all($pattern, $subject, $matches)) {
            throw new MatchException($err_msg);
        }
        return $matches;
    }

    public static function preg_match_all($pattern, $subject, array &$matches = null, $flags = PREG_SET_ORDER)
    {
        return (false !== preg_match_all($pattern, $subject, $matches, $flags));
    }

    public function requestJsonDecode($method, $uri = '', array $options = [], $err_msg = '发送请求失败')
    {
        return json_decode($this->request($method, $uri, $options, $err_msg), true);
    }

    public function request($method, $uri = '', array $options = [], $err_msg = '发送请求失败')
    {
        try {
            $response = $this->client->request($method, $uri, $options);
        } catch (\Exception $e) {
            throw new RequestException($err_msg);
        }
        if (!$content = $response->getBody()->getContents()) {
            throw new RequestException($err_msg);
        }
        if (self::preg_match_all('/<meta(.*?)>/isu', $content, $meta_matches)) {
            foreach ($meta_matches as $meta_match) {
                if (self::preg_match('/Refresh.*?url=(.+?)"/isu', $meta_match[1], $url_matches)) {
                    return $this->request($method, $url_matches[1], [
                            'base_uri' => $uri,
                        ] + $options);
                }
            }
        }
        return $content;
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

    public function getConfig($option = null)
    {
        return $this->client->getConfig($option);
    }

    public function __construct(array $config = [])
    {
        $defaults = [
            'cookies' => true,
            'verify' => false,
        ];
        $this->client = new Client($config + $defaults);
    }
}
