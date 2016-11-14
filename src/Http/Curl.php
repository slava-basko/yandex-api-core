<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 9/27/16
 */

namespace Yandex\Http;

class Curl implements CurlInterface
{
    /**
     * @var resource
     */
    private $ch;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var array
     */
    private $responseHeaders = [];

    /**
     * Curl constructor.
     * @param $url
     */
    public function init($url)
    {
        $this->ch = curl_init($url);
    }

    /**
     * Close
     */
    public function close()
    {
        curl_close($this->ch);
        $this->ch = null;
        $this->headers = [];
        $this->responseHeaders = [];
    }

    /**
     * @param $options
     */
    public function setOptions(array $options = [])
    {
        $options = array_replace([
            CURLOPT_HEADERFUNCTION => [$this, 'headerHandler'],
            CURLOPT_HTTPHEADER => array_values($this->headers)
        ], $options);
        curl_setopt_array($this->ch, $options);
    }

    /**
     * @return mixed
     */
    public function exec()
    {
        return curl_exec($this->ch);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return (int)curl_getinfo($this->ch, CURLINFO_RESPONSE_CODE);
    }

    /**
     * @return string
     */
    public function getError()
    {
        return curl_error($this->ch);
    }

    /**
     * @return int
     */
    public function getErrorCode()
    {
        return curl_errno($this->ch);
    }

    /**
     * @param $header
     * @param $value
     */
    public function addHeader($header, $value)
    {
        $this->headers[$header] = $header . ': ' . $value;
    }

    /**
     * @return array
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection
     * @param $curl
     * @param $header
     * @return int
     */
    private function headerHandler(/** @noinspection PhpUnusedParameterInspection */$curl, $header)
    {
        $length = strlen($header);
        $header = str_replace(["\r", "\n"], '', $header);
        if (strpos($header, 'HTTP/') !== 0 and $pos = strpos($header, ':')) {
            $this->responseHeaders[trim(substr($header, 0, $pos))] = trim(substr($header, $pos + 1));
        }
        return $length;
    }
}