<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 9/20/16
 */

namespace Yandex\Exception;

class BadRequestException extends YandexException
{
    protected $code = 400;
}