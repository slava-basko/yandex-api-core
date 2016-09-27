<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 9/2/16
 */

namespace Yandex;

use Yandex\Http\Response;
use Yandex\Utils\Hash;
use Yandex\Utils\Json;

/**
 * @param $domainName
 * @return bool
 */
function isValidDomainName($domainName)
{
    return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domainName) //valid chars check
        && preg_match("/^.{1,253}$/", $domainName) //overall length check
        && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domainName)   ); //length of each label
}

/**
 * @param Response $response
 * @return string
 */
function apiJsonErrorToMessage(Response $response)
{
    $errorMessage = '';
    $responseData = Json::decode($response->getBody());

    $errorMessage .= Hash::get($responseData, 'error_code', '');
    $errorMessage .= ' : ';
    $errorMessage .= Hash::get($responseData, 'error_message', '');

    return $errorMessage;
}