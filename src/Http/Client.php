<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 8/11/16
 */

namespace Yandex\Api;

use Yandex\Action\ActionInterface;
use Yandex\Action\DataActionInterface;
use Yandex\ActionHandler\ActionHandlerInterface;
use Yandex\Http\Response;

final class Client
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
     * @param ActionInterface $action
     * @return mixed
     * @throws \Exception
     */
    public function call(ActionInterface $action)
    {
        $options = [];
        $options[CURLOPT_CUSTOMREQUEST] = strtoupper((string) $action->getHttpMethod());
        $options[CURLOPT_TIMEOUT] = 30;
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_FAILONERROR] = true;
        $options[CURLOPT_HTTPHEADER] = [
            'Authorization: OAuth ' . (string)$action->getToken()
        ];

        if ($action instanceof DataActionInterface) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = (string)$action->getBody();
        }

        $ch = curl_init($action->getUrl());
        curl_setopt_array($ch, $options);
        $rawResponse = curl_exec($ch);

        if ($rawResponse === false) {
            throw new \Exception(curl_error($ch), curl_errno($ch));
        }

        $response = new Response(
            (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE),
            curl_getinfo($ch, CURLINFO_HEADER_OUT),
            $rawResponse
        );

        curl_close($ch);

        /**
         * @var ActionHandlerInterface $actionHandler
         */
        $actionHandler = new $this->actionHandlerMap[get_class($action)]();
        return $actionHandler->handle($response);
    }
}
