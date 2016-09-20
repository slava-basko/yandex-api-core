<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 8/11/16
 */

namespace Yandex\Http;

use Yandex\Action\ActionInterface;
use Yandex\Action\DataActionInterface;
use Yandex\ActionHandler\ActionHandlerInterface;
use Yandex\Exception\UnsupportedActionException;

class Client
{
    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientPassword;

    /**
     * @var array
     */
    private $responseHeaders = [];

    /**
     * [StatAction::class => StatActionHandler::class]
     *
     * @var array
     */
    private $actionHandlerMap = [];

    /**
     * @var array
     */
    private $headers = [];

    /**
     * Client constructor.
     * @param string $apiUrl
     * @param $clientId
     * @param $clientPassword
     * @internal param string $apiKey
     */
    public function __construct($apiUrl, $clientId, $clientPassword)
    {
        if (filter_var($apiUrl, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException('Invalid URL given.');
        }

        $this->apiUrl = $apiUrl;
        $this->clientId = $clientId;
        $this->clientPassword = $clientPassword;
    }

    /**
     * @param string $action
     * @param string $handler
     */
    public function addActionHandler($action, $handler)
    {
        $this->actionHandlerMap[$action] = $handler;
    }

    /**
     * @param $header
     * @param $value
     */
    public function addHeader($header, $value)
    {
        $this->headers[] = $header . ': ' . $value;
    }

    /**
     * @param ActionInterface $action
     * @return mixed
     * @throws \Exception
     */
    public function call(ActionInterface $action)
    {
        $actionClass = get_class($action);
        if (array_key_exists($actionClass, $this->actionHandlerMap) == false) {
            throw new UnsupportedActionException('Action ' . $actionClass . ' not supported.');
        }

        $options = [];
        $options[CURLOPT_HEADERFUNCTION] = [$this, 'headerHandler'];
        $options[CURLOPT_CUSTOMREQUEST] = strtoupper((string) $action->getHttpMethod());
        $options[CURLOPT_TIMEOUT] = 30;
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_FAILONERROR] = false;
        $options[CURLOPT_HTTPHEADER] = array_merge([
            'Authorization: OAuth ' . (string)$action->getToken()
        ], $this->headers);

        if ($action instanceof DataActionInterface) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = (string)$action->getBody();
        }

        $ch = curl_init($this->apiUrl . $action->getUrl());
        curl_setopt_array($ch, $options);
        $rawResponse = curl_exec($ch);

        if ($rawResponse === false) {
            throw new \Exception(curl_error($ch), curl_errno($ch));
        }

        $response = new Response(
            (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE),
            $this->responseHeaders,
            $rawResponse
        );

        curl_close($ch);
        $this->responseHeaders = [];

        /**
         * @var ActionHandlerInterface $actionHandler
         */
        $actionHandler = new $this->actionHandlerMap[$actionClass]();
        return $actionHandler->handle($response);
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
