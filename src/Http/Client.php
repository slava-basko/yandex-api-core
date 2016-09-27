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

class Client implements HttpClientInterface
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
     * [StatAction::class => StatActionHandler::class]
     *
     * @var array
     */
    private $actionHandlerMap = [];
    
    /**
     * @var CurlInterface
     */
    private $curl;

    /**
     * Client constructor.
     * @param string $apiUrl
     * @param $clientId
     * @param $clientPassword
     * @param CurlInterface $curl
     * @internal param string $apiKey
     */
    public function __construct($apiUrl, $clientId, $clientPassword, CurlInterface $curl)
    {
        if (filter_var($apiUrl, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException('Invalid URL given.');
        }

        $this->apiUrl = $apiUrl;
        $this->clientId = $clientId;
        $this->clientPassword = $clientPassword;
        $this->curl = $curl;
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
        $this->curl->addHeader($header, $value);
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
        $options[CURLOPT_CUSTOMREQUEST] = strtoupper((string) $action->getHttpMethod());
        $options[CURLOPT_TIMEOUT] = 30;
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_FAILONERROR] = false;

        if ($action instanceof DataActionInterface) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = (string)$action->getBody();
        }
        
        $this->curl->init($this->apiUrl . $action->getUrl());
        $this->curl->addHeader('Authorization', 'OAuth ' . (string)$action->getToken());
        $this->curl->setOptions($options);
        $rawResponse = $this->curl->exec();

        if ($rawResponse === false) {
            throw new \Exception($this->curl->getError(), $this->curl->getErrorCode());
        }

        $response = new Response(
            $this->curl->getStatusCode(),
            $this->curl->getResponseHeaders(),
            $rawResponse
        );

        $this->curl->close();

        /**
         * @var ActionHandlerInterface $actionHandler
         */
        $actionHandler = new $this->actionHandlerMap[$actionClass]();
        return $actionHandler->handle($response);
    }
}
