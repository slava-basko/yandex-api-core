<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 9/27/16
 */

namespace Yandex\Tests\Auth;

use Yandex\Auth\Token;

class TokenTest extends \PHPUnit_Framework_TestCase
{
    public function test_token_creation()
    {
        $this->expectException('\InvalidArgumentException');
        new Token(123);
    }
}