<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 9/2/16
 */

namespace Yandex\ActionHandler;

use Yandex\Http\Response;

interface ActionHandlerInterface
{
    public function handle(Response $response);
}
