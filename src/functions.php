<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 9/2/16
 */

namespace Yandex;

use Yandex\Http\Response;
use Yandex\Utils\Hash;
use Yandex\Utils\SimpleXMLReader;

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
function apiErrorToMessage(Response $response)
{
    $reasonBody = '';

    $reader = new SimpleXMLReader;
    if ($reader->XML($response->getBody()) == false) {
        throw new \InvalidArgumentException('Invalid XML.');
    }
    $reader->registerCallback('error', function ($reader) use (&$reasonBody) {
        /**
         * @var SimpleXMLReader $reader
         */
        $element = $reader->expandSimpleXml();
        $attributes = (array)$element->attributes();
        $reasonBody .= Hash::get($attributes, '@attributes.code', '');
        $reasonBody .= ' : ';
        $reasonBody .= (string)$element->message;
    });
    $reader->parse();
    $reader->close();

    return $reasonBody;
}