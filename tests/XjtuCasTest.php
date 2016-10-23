<?php
namespace XjtuApi\Tests;

use XjtuApi\XjtuCas;

class XjtuCasTest extends \PHPUnit_Framework_TestCase
{
    public function testLoginWrong()
    {
        $xjtuCas = new XjtuCas(config('client'));
        $response = $xjtuCas->login(config('wrong')['username'], config('wrong')['password']);
        test_dump($response);
        $this->assertLessThan(0, $response['code']);
    }

    public function testLogin()
    {
        $xjtuCas = new XjtuCas(config('client'));
        $response = $xjtuCas->login(config('XjtuCas')['username'], config('XjtuCas')['password']);
        test_dump($response);
        $this->assertGreaterThanOrEqual(0, $response['code']);
        return $xjtuCas;
    }

    /**
     * @depends testLogin
     */
    public function testLogout(XjtuCas $xjtuCas)
    {
        $response = $xjtuCas->logout();
        test_dump($response);
        $this->assertGreaterThanOrEqual(0, $response['code']);
        return $xjtuCas;
    }
}
