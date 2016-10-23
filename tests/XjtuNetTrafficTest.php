<?php
namespace XjtuApi\Tests;

use XjtuApi\XjtuNetTraffic;

class XjtuNetTrafficTest extends \PHPUnit_Framework_TestCase
{
    public function testLoginWrong()
    {
        $xjtuNetTraffic = new XjtuNetTraffic(config('client'));
        $response = $xjtuNetTraffic->login(config('wrong')['username'], config('wrong')['password']);
        test_dump($response);
        $this->assertLessThan(0, $response['code']);
    }

    public function testLogin()
    {
        $xjtuNetTraffic = new XjtuNetTraffic(config('client'));
        $response = $xjtuNetTraffic->login(config('XjtuNetTraffic')['username'], config('XjtuNetTraffic')['password']);
        test_dump($response);
        $this->assertGreaterThanOrEqual(0, $response['code']);
        return $xjtuNetTraffic;
    }

    /**
     * @depends testLogin
     */
    public function testCurrent(XjtuNetTraffic $xjtuNetTraffic)
    {
        $response = $xjtuNetTraffic->current();
        test_dump($response);
        $this->assertGreaterThanOrEqual(0, $response['code']);
    }

    /**
     * @depends testLogin
     */
    public function testHistory(XjtuNetTraffic $xjtuNetTraffic)
    {
        $response = $xjtuNetTraffic->history();
        test_dump($response);
        $this->assertGreaterThanOrEqual(0, $response['code']);
    }

    /**
     * @depends testLogin
     */
    public function testDetails(XjtuNetTraffic $xjtuNetTraffic)
    {
        $response = $xjtuNetTraffic->details();
        test_dump($response);
        $this->assertGreaterThanOrEqual(0, $response['code']);
        $response = $xjtuNetTraffic->details(2016, 10, 2);
        test_dump($response);
        $this->assertGreaterThanOrEqual(0, $response['code']);
    }

    /**
     * @depends testLogin
     */
    public function testState(XjtuNetTraffic $xjtuNetTraffic)
    {
        $response = $xjtuNetTraffic->state();
        test_dump($response);
        $this->assertGreaterThanOrEqual(0, $response['code']);
    }

    /**
     * @depends testLogin
     */
    public function testAccount(XjtuNetTraffic $xjtuNetTraffic)
    {
        $response = $xjtuNetTraffic->account();
        test_dump($response);
        $this->assertGreaterThanOrEqual(0, $response['code']);
    }

    /**
     * @depends testLogin
     */
    public function testUserinfo(XjtuNetTraffic $xjtuNetTraffic)
    {
        $response = $xjtuNetTraffic->userinfo();
        test_dump($response);
        $this->assertGreaterThanOrEqual(0, $response['code']);
    }

    /**
     * @depends testLogin
     */
    public function testChangepwd(XjtuNetTraffic $xjtuNetTraffic)
    {
        $response = $xjtuNetTraffic->changepwd(config('XjtuNetTraffic')['password'], config('XjtuNetTraffic')['password']);
        test_dump($response);
        $this->assertGreaterThanOrEqual(0, $response['code']);
    }

    /**
     * @depends testLogin
     */
    public function testLogout(XjtuNetTraffic $xjtuNetTraffic)
    {
        $response = $xjtuNetTraffic->logout();
        test_dump($response);
        $this->assertGreaterThanOrEqual(0, $response['code']);
    }
}
