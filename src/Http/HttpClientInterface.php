<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 9/26/16
 */

namespace Yandex\Http;

use Yandex\Action\ActionInterface;

interface HttpClientInterface
{
    /**
     * @param ActionInterface $action
     * @return mixed
     */
    public function call(ActionInterface $action);
}