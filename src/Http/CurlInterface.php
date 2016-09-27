<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 9/27/16
 */

namespace Yandex\Http;

interface CurlInterface
{
    /**
     * Curl constructor.
     * @param $url
     */
    public function init($url);

    /**
     * Close
     */
    public function close();

    /**
     * @param $options
     */
    public function setOptions(array $options = []);

    /**
     * @return mixed
     */
    public function exec();

    /**
     * @return int
     */
    public function getStatusCode();

    /**
     * @return string
     */
    public function getError();

    /**
     * @return int
     */
    public function getErrorCode();

    /**
     * @param $header
     * @param $value
     */
    public function addHeader($header, $value);

    /**
     * @return array
     */
    public function getResponseHeaders();
}