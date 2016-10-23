<?php
namespace XjtuApi\Tests;

use GuzzleHttp\Client;
use XjtuApi\XjtuCas;

class XjtuCasTest extends \PHPUnit_Framework_TestCase
{
    public function testLoginWrong()
    {
        $xjtuCas = new XjtuCas();
        $response = $xjtuCas->login(config('Wrong')['username'], config('Wrong')['password']);
        $this->assertLessThan(0, $response['code']);
    }

    public function testLogin()
    {
        $xjtuCas = new XjtuCas();
        $response = $xjtuCas->login(config('XjtuCas')['username'], config('XjtuCas')['password']);
        $this->assertGreaterThanOrEqual(0, $response['code']);
        return $xjtuCas;
    }

    /**
     * @depends testLogin
     */
    public function testLogout(XjtuCas $xjtuCas)
    {
        $response = $xjtuCas->logout();
        $this->assertGreaterThanOrEqual(0, $response['code']);
        return $xjtuCas;
    }

    public function testGetClient()
    {
        $xjtuCas = new XjtuCas();
        $client = $xjtuCas->getClient();
        $this->assertInstanceOf(Client::class, $client);
    }

}
