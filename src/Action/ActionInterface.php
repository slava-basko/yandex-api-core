<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 9/2/16
 */

namespace Yandex\Action;

use Yandex\Auth\Token;

interface ActionInterface
{
    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return string
     */
    public function getHttpMethod();

    /**
     * @return Token
     */
    public function getToken();
}
