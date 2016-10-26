<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 10/26/16
 */

namespace Yandex\Http;

class ClientRoot
{
    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientPassword;

    /**
     * [StatAction::class => StatActionHandler::class]
     *
     * @var array
     */
    protected $actionHandlerMap = [];
}