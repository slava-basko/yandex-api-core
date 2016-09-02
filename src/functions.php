<?php
/**
 * Created by Slava Basko <basko.slava@gmail.com>
 * Date: 9/2/16
 */

namespace Yandex;

function isValidDomainName($domainName)
{
    return (preg_match("/^[a-z\d](-*[a-z\d])*$/i", $domainName) //valid chars check
        && preg_match("/^.{1,253}$/", $domainName) //overall length check
        && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domainName)   ); //length of each label
}