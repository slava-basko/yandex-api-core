<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 10/26/16
 */

namespace Yandex\Http;

use Psr\Log\LoggerInterface;
use Yandex\Action\ActionInterface;

/**
 * Class LoggableClient
 * @package Yandex\Http
 *
 * @method void addActionHandler(string $action, string $handler)
 * @method void addHeader(string $header, string $value);
 */
class LoggableClient extends ClientRoot implements HttpClientInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Client
     */
    private $client;

    /**
     * LoggableClient constructor.
     * @param $apiUrl
     * @param $clientId
     * @param $clientPassword
     * @param CurlInterface $curl
     * @param LoggerInterface $logger
     */
    public function __construct(
        $apiUrl,
        $clientId,
        $clientPassword,
        CurlInterface $curl,
        LoggerInterface $logger
    )
    {
        $this->client = new Client(
            $apiUrl,
            $clientId,
            $clientPassword,
            $curl
        );
        $this->logger = $logger;
    }

    /**
     * @param ActionInterface $action
     * @return mixed
     * @throws \Exception
     */
    public function call(ActionInterface $action)
    {
        $this->logger->debug('Call yandex API', [
            'action' => get_class($action),
            'url' => $this->client->apiUrl . $action->getUrl(),
            'client_id' => $this->client->clientId
        ]);

        try {
            $result = $this->client->call($action);
        } catch (\Exception $ex) {
            $this->logger->debug('Exception while calling API', [
                'exception_class' => get_class($ex),
                'exception_code' => $ex->getCode(),
                'exception_message' => $ex->getMessage(),
                'exception_trace' => $ex->getTrace(),
                'exception_file' => $ex->getFile(),
                'exception_line' => $ex->getLine()
            ]);
            throw $ex;
        }

        $this->logger->debug('Call result', [
            'result' => var_export($result, true)
        ]);

        return $result;
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->client, $method], $arguments);
    }
}