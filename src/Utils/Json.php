<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 9/19/16
 */

namespace Yandex\Utils;

use Yandex\Exception\JsonException;

class Json
{
    /**
     * @param $jsonString
     * @return mixed
     * @throws JsonException
     */
    public static function decode($jsonString)
    {
        if (is_string($jsonString) and empty($jsonString)) {
            return '';
        }

        $decodedValue = json_decode($jsonString, true);

        switch (json_last_error()) {
            case JSON_ERROR_DEPTH:
                throw new JsonException('The maximum stack depth has been exceeded.');
                break;
            case JSON_ERROR_STATE_MISMATCH:
                throw new JsonException('Invalid or malformed JSON.');
                break;
            case JSON_ERROR_CTRL_CHAR:
                throw new JsonException('Control character error, possibly incorrectly encoded.');
                break;
            case JSON_ERROR_SYNTAX:
                throw new JsonException('Syntax error.');
                break;
            case JSON_ERROR_UTF8:
                throw new JsonException('Malformed UTF-8 characters, possibly incorrectly encoded.');
                break;
            case JSON_ERROR_RECURSION:
                throw new JsonException('One or more recursive references in the value to be encoded.');
                break;
            case JSON_ERROR_INF_OR_NAN:
                throw new JsonException('One or more NAN or INF values in the value to be encoded.');
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                throw new JsonException('A value of a type that cannot be encoded was given.');
                break;
            default:
                break;
        }

        return $decodedValue;
    }

    /**
     * @param $data
     * @return string
     */
    public static function encode($data)
    {
        return json_encode($data);
    }
}