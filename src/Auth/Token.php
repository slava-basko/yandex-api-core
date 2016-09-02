<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 9/2/16
 */

namespace Yandex\Auth;

final class Token
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * Token constructor.
     * @param $accessToken
     */
    public function __construct($accessToken)
    {
        if (is_string($accessToken) == false) {
            throw new \InvalidArgumentException('Invalid token param.');
        }
        $this->accessToken = $accessToken;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->accessToken;
    }
}