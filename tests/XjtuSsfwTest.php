<?php
namespace XjtuApi\Tests;

use XjtuApi\XjtuCas;
use XjtuApi\XjtuSsfw;

class XjtuSsfwTest extends \PHPUnit_Framework_TestCase
{
    public function testLogin()
    {
        $xjtuCas = new XjtuCas(config('client'));
        $response = $xjtuCas->login(config('XjtuCas')['username'], config('XjtuCas')['password']);
        test_dump($response);
        $xjtuSsfw = new XjtuSsfw();
        $response = $xjtuSsfw->login($xjtuCas);
        var_dump($response);
        $this->assertGreaterThanOrEqual(0, $response['code']);
        return $xjtuSsfw;
    }
}
