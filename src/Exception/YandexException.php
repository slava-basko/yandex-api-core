<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 9/5/16
 */

namespace Yandex\Exception;

class YandexException extends \Exception
{
    /**
     * @var array
     */
    protected $extra = [];

    /**
     * YandexException constructor.
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     * @param array $extra
     */
    public function __construct($message = '', $code = 0, \Exception $previous = null, $extra = [])
    {
        parent::__construct($message, $code, $previous);
        $this->extra = $extra;
    }

    /**
     * @return array
     */
    public function getExtra()
    {
        return $this->extra;
    }
}