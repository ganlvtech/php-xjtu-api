<?php
namespace XjtuApi\Tests;

use XjtuApi\Cas;
use XjtuApi\Exception\MatchException;

class CasTest extends \PHPUnit_Framework_TestCase
{
    public function testLoginWrong()
    {
        $cas = new Cas(config('client'));
        try {
            $response = $cas->login(config('wrong')['username'], config('wrong')['password']);
            test_dump($response);
        } catch (MatchException $e) {
            test_dump($e->getMessage());
        }
    }

    public function testLogin()
    {
        $cas = new Cas(config('client'));
        $response = $cas->login(config('Cas')['username'], config('Cas')['password']);
        test_dump($response);
        return $cas;
    }

    /**
     * @depends testLogin
     *
     * @param Cas $cas
     */
    public function testLogout(Cas $cas)
    {
        $response = $cas->logout();
        test_dump($response);
    }
}
