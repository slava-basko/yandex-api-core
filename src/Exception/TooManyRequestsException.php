<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 9/29/16
 */

namespace Yandex\Exception;

class TooManyRequestsException extends YandexException
{
    protected $code = 429;
}