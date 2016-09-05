<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @since         2.2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Yandex\Utils;


use ArrayAccess;
use InvalidArgumentException;

/**
 * Class Hash - modified
 * @package Yandex\Utils
 */
class Hash
{
    /**
     * @param $data
     * @param $path
     * @param null $default
     * @return mixed|null
     */
    public static function get($data, $path, $default = null)
    {
        if (!(is_array($data) || $data instanceof ArrayAccess)) {
            throw new InvalidArgumentException(
                'Invalid data type, must be an array or \ArrayAccess instance.'
            );
        }
        if (empty($data) || $path === null || $path === '') {
            return $default;
        }
        if (is_string($path) || is_numeric($path)) {
            $parts = explode('.', $path);
        } else {
            if (!is_array($path)) {
                throw new InvalidArgumentException(sprintf(
                    'Invalid Parameter %s, should be dot separated path or array.',
                    $path
                ));
            }
            $parts = $path;
        }
        switch (count($parts)) {
            case 1:
                return isset($data[$parts[0]]) ? $data[$parts[0]] : $default;
            case 2:
                return isset($data[$parts[0]][$parts[1]]) ? $data[$parts[0]][$parts[1]] : $default;
            case 3:
                return isset($data[$parts[0]][$parts[1]][$parts[2]]) ? $data[$parts[0]][$parts[1]][$parts[2]] : $default;
            default:
                foreach ($parts as $key) {
                    if ((is_array($data) || $data instanceof ArrayAccess) && isset($data[$key])) {
                        $data = $data[$key];
                    } else {
                        return $default;
                    }
                }
        }
        return $data;
    }
}