<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 9/20/16
 */

namespace Yandex\Exception;

class NotFoundException extends YandexException
{
    protected $code = 404;
}