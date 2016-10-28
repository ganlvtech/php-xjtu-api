<?php
namespace XjtuApi\Tests;

use XjtuApi\Cas;
use XjtuApi\Ssfw;

class XjtuSsfwTest extends \PHPUnit_Framework_TestCase
{
    public function testLogin()
    {
        $cas = new Cas(config('client'));
        $response = $cas->login(config('Cas')['username'], config('Cas')['password']);
        test_dump($response);
        $ssfw = new Ssfw();
        $response = $ssfw->login($cas);
        var_dump($response);
        return $ssfw;
    }
}
