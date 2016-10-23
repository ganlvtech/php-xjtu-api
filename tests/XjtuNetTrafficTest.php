<?php
namespace XjtuApi\Tests;

use XjtuApi\XjtuNetTraffic;

class XjtuNetTrafficTest extends \PHPUnit_Framework_TestCase
{
    public function testLoginWrong()
    {
        $xjtuNetTraffic = new XjtuNetTraffic();
        $response = $xjtuNetTraffic->login(config('Wrong')['username'], config('Wrong')['password']);
        $this->assertLessThan(0, $response['code']);
    }

    public function testLogin()
    {
        $xjtuNetTraffic = new XjtuNetTraffic();
        $response = $xjtuNetTraffic->login(config('xjtuNetTraffic')['username'], config('xjtuNetTraffic')['password']);
        $this->assertGreaterThanOrEqual(0, $response['code']);
        return $xjtuNetTraffic;
    }

    /**
     * @depends testLogin
     */
    public function testCurrent(XjtuNetTraffic $xjtuNetTraffic)
    {
        $response = $xjtuNetTraffic->current();
        $this->assertGreaterThanOrEqual(0, $response['code']);
    }

    /**
     * @depends testLogin
     */
    public function testHistory(XjtuNetTraffic $xjtuNetTraffic)
    {
        $response = $xjtuNetTraffic->history();
        $this->assertGreaterThanOrEqual(0, $response['code']);
    }

    /**
     * @depends testLogin
     */
    public function testDetails(XjtuNetTraffic $xjtuNetTraffic)
    {
        $response = $xjtuNetTraffic->details();
        $this->assertGreaterThanOrEqual(0, $response['code']);
        $response = $xjtuNetTraffic->details(2016, 10, 2);
        $this->assertGreaterThanOrEqual(0, $response['code']);
    }

    /**
     * @depends testLogin
     */
    public function testState(XjtuNetTraffic $xjtuNetTraffic)
    {
        $response = $xjtuNetTraffic->state();
        $this->assertGreaterThanOrEqual(0, $response['code']);
    }

    /**
     * @depends testLogin
     */
    public function testAccount(XjtuNetTraffic $xjtuNetTraffic)
    {
        $response = $xjtuNetTraffic->account();
        $this->assertGreaterThanOrEqual(0, $response['code']);
    }

    /**
     * @depends testLogin
     */
    public function testUserinfo(XjtuNetTraffic $xjtuNetTraffic)
    {
        $response = $xjtuNetTraffic->userinfo();
        $this->assertGreaterThanOrEqual(0, $response['code']);
    }

    /**
     * @depends testLogin
     */
    public function testChangepwd(XjtuNetTraffic $xjtuNetTraffic)
    {
        $response = $xjtuNetTraffic->changepwd(config('xjtuNetTraffic')['password'], config('xjtuNetTraffic')['password']);
        $this->assertGreaterThanOrEqual(0, $response['code']);
    }

    /**
     * @depends testLogin
     */
    public function testLogout(XjtuNetTraffic $xjtuNetTraffic)
    {
        $response = $xjtuNetTraffic->logout();
        $this->assertGreaterThanOrEqual(0, $response['code']);
    }
}
