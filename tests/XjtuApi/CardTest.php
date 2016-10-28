<?php
namespace XjtuApi\Tests;

use XjtuApi\Card;
use XjtuApi\Cas;

class CardTest extends \PHPUnit_Framework_TestCase
{
    public function testLogin()
    {
        $cas = new Cas(config('client'));
        $response = $cas->login(config('Cas')['username'], config('Cas')['password']);
        test_dump($response);
        $card = new Card();
        $response = $card->login($cas);
        var_dump($response);
        return $card;
    }
}
