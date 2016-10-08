yandex-api-core
===============
Core functionality for SDK

How to use?
-----------
```php
use Yandex\Http\Client;
use Yandex\Auth\Token;
use Yandex\Http\Curl;
use YandexWebmaster\Action\GetUserIdAction;
use YandexWebmaster\ActionHandler\GetUserIdActionHandler;

$client = new Client('url', 'client_id', 'client_password', new Curl());
$client->addActionHandler(GetUserIdAction::class, GetUserIdActionHandler::class);
$client->addHeader('Content-type', 'application/json');

$client->call('<action>');
```