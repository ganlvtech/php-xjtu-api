<?php
namespace XjtuApi\Tests;

use XjtuApi\Exception\MatchException;
use XjtuApi\NetTraffic;

class NetTrafficTest extends \PHPUnit_Framework_TestCase
{
    public function testLoginWrong()
    {
        $netTraffic = new NetTraffic(config('client'));
        try {
            $response = $netTraffic->login(config('wrong')['username'], config('wrong')['password']);
            test_dump($response);
        } catch (MatchException $e) {
            test_dump($e->getMessage());
        }
    }

    public function testLogin()
    {
        $netTraffic = new NetTraffic(config('client'));
        $response = $netTraffic->login(config('NetTraffic')['username'], config('NetTraffic')['password']);
        test_dump($response);
        return $netTraffic;
    }

    /**
     * @depends testLogin
     *
     * @param NetTraffic $netTraffic
     */
    public function testCurrent(NetTraffic $netTraffic)
    {
        $response = $netTraffic->current();
        test_dump($response);
    }

    /**
     * @depends testLogin
     *
     * @param NetTraffic $netTraffic
     */
    public function testHistory(NetTraffic $netTraffic)
    {
        $response = $netTraffic->history();
        test_dump($response);
    }

    /**
     * @depends testLogin
     *
     * @param NetTraffic $netTraffic
     */
    public function testDetails(NetTraffic $netTraffic)
    {
        $response = $netTraffic->details();
        test_dump($response);
        $response = $netTraffic->details(2016, 10, 2);
        test_dump($response);
    }

    /**
     * @depends testLogin
     *
     * @param NetTraffic $netTraffic
     */
    public function testState(NetTraffic $netTraffic)
    {
        $response = $netTraffic->state();
        test_dump($response);
    }

    /**
     * @depends testLogin
     *
     * @param NetTraffic $netTraffic
     */
    public function testAccount(NetTraffic $netTraffic)
    {
        $response = $netTraffic->account();
        test_dump($response);
    }

    /**
     * @depends testLogin
     *
     * @param NetTraffic $netTraffic
     */
    public function testUserinfo(NetTraffic $netTraffic)
    {
        $response = $netTraffic->userinfo();
        test_dump($response);
    }

    /**
     * @depends testLogin
     *
     * @param NetTraffic $netTraffic
     */
    public function testChangepwd(NetTraffic $netTraffic)
    {
        $response = $netTraffic->changepwd(config('NetTraffic')['password'], config('NetTraffic')['password']);
        test_dump($response);
    }

    /**
     * @depends testLogin
     *
     * @param NetTraffic $netTraffic
     */
    public function testLogout(NetTraffic $netTraffic)
    {
        $response = $netTraffic->logout();
        test_dump($response);
    }
}
